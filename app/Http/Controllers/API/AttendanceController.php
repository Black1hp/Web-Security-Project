<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\StudentAttendance;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of attendance records for a course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::findOrFail($request->course_id);
        
        // Check if the user is authorized to view attendance records for this course
        $this->authorize('viewAttendance', $course);
        
        $query = AttendanceRecord::where('course_id', $course->id);
        
        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('session_date', [$request->start_date, $request->end_date]);
        }
        
        $records = $query->orderBy('session_date', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $records
        ]);
    }

    /**
     * Store a newly created attendance record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'session_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'topic' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::findOrFail($request->course_id);
        
        // Check if the user is authorized to create attendance records for this course
        $this->authorize('createAttendance', $course);
        
        try {
            DB::beginTransaction();
            
            // Create the attendance record
            $record = AttendanceRecord::create([
                'course_id' => $course->id,
                'session_date' => $request->session_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'topic' => $request->topic,
                'recorded_by' => Auth::id(),
            ]);
            
            // Create student attendance records for all enrolled students
            $enrollments = Enrollment::where('course_id', $course->id)
                                   ->where('status', 'active')
                                   ->get();
            
            foreach ($enrollments as $enrollment) {
                StudentAttendance::create([
                    'attendance_record_id' => $record->id,
                    'user_id' => $enrollment->user_id,
                    'status' => 'absent', // Default to absent until marked present
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Attendance record created successfully',
                'data' => $record
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create attendance record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified attendance record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $record = AttendanceRecord::with('studentAttendances.user')->findOrFail($id);
        
        // Check if the user is authorized to view this attendance record
        $this->authorize('view', $record);
        
        return response()->json([
            'success' => true,
            'data' => $record
        ]);
    }

    /**
     * Update the specified attendance record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $record = AttendanceRecord::findOrFail($id);
        
        // Check if the user is authorized to update this attendance record
        $this->authorize('update', $record);
        
        $validator = Validator::make($request->all(), [
            'session_date' => 'sometimes|required|date',
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time' => 'sometimes|required|date_format:H:i|after:start_time',
            'topic' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $record->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Attendance record updated successfully',
                'data' => $record
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update attendance record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified attendance record from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = AttendanceRecord::findOrFail($id);
        
        // Check if the user is authorized to delete this attendance record
        $this->authorize('delete', $record);
        
        try {
            DB::beginTransaction();
            
            // Delete associated student attendance records
            StudentAttendance::where('attendance_record_id', $record->id)->delete();
            
            // Delete the attendance record
            $record->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Attendance record deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attendance record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update student attendance status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStudentAttendance(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'student_attendances' => 'required|array',
            'student_attendances.*.user_id' => 'required|exists:users,id',
            'student_attendances.*.status' => 'required|in:present,absent,late,excused',
            'student_attendances.*.check_in_time' => 'nullable|date_format:H:i',
            'student_attendances.*.remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $record = AttendanceRecord::findOrFail($id);
        
        // Check if the user is authorized to update student attendance for this record
        $this->authorize('updateStudentAttendance', $record);
        
        try {
            DB::beginTransaction();
            
            $results = [];
            
            foreach ($request->student_attendances as $attendanceData) {
                $studentAttendance = StudentAttendance::where('attendance_record_id', $record->id)
                                                   ->where('user_id', $attendanceData['user_id'])
                                                   ->first();
                
                if (!$studentAttendance) {
                    // Create a new student attendance record if it doesn't exist
                    $studentAttendance = StudentAttendance::create([
                        'attendance_record_id' => $record->id,
                        'user_id' => $attendanceData['user_id'],
                        'status' => $attendanceData['status'],
                        'check_in_time' => $attendanceData['check_in_time'] ?? null,
                        'remarks' => $attendanceData['remarks'] ?? null,
                    ]);
                    
                    $results[] = [
                        'user_id' => $attendanceData['user_id'],
                        'status' => 'created',
                        'attendance' => $studentAttendance
                    ];
                } else {
                    // Update existing student attendance record
                    $studentAttendance->update([
                        'status' => $attendanceData['status'],
                        'check_in_time' => $attendanceData['check_in_time'] ?? $studentAttendance->check_in_time,
                        'remarks' => $attendanceData['remarks'] ?? $studentAttendance->remarks,
                    ]);
                    
                    $results[] = [
                        'user_id' => $attendanceData['user_id'],
                        'status' => 'updated',
                        'attendance' => $studentAttendance
                    ];
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Student attendance updated successfully',
                'data' => $results
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit an excuse for absence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function submitExcuse(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'excuse_reason' => 'required|string',
            'documentation' => 'nullable|file|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $studentAttendance = StudentAttendance::findOrFail($id);
        
        // Check if the user is authorized to submit an excuse for this attendance
        $this->authorize('submitExcuse', $studentAttendance);
        
        try {
            $documentationPath = null;
            $hasDocumentation = false;
            
            // Upload documentation if provided
            if ($request->hasFile('documentation')) {
                $file = $request->file('documentation');
                $documentationPath = $file->store('attendance_documentation');
                $hasDocumentation = true;
            }
            
            // Update the student attendance record
            $studentAttendance->update([
                'status' => 'excused',
                'excuse_reason' => $request->excuse_reason,
                'has_documentation' => $hasDocumentation,
                'documentation_path' => $documentationPath,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Excuse submitted successfully',
                'data' => $studentAttendance
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit excuse',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify an excuse for absence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verifyExcuse(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_verified' => 'required|boolean',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $studentAttendance = StudentAttendance::findOrFail($id);
        
        // Check if the user is authorized to verify excuses
        $this->authorize('verifyExcuse', $studentAttendance);
        
        try {
            if ($request->is_verified) {
                // Verify the excuse
                $studentAttendance->update([
                    'status' => 'excused',
                    'verified_by' => Auth::id(),
                    'remarks' => $request->remarks,
                ]);
                
                $message = 'Excuse verified successfully';
            } else {
                // Reject the excuse
                $studentAttendance->update([
                    'status' => 'absent',
                    'verified_by' => Auth::id(),
                    'remarks' => $request->remarks,
                ]);
                
                $message = 'Excuse rejected';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $studentAttendance
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify excuse',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance statistics for a course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function courseStatistics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::findOrFail($request->course_id);
        
        // Check if the user is authorized to view attendance statistics for this course
        $this->authorize('viewAttendance', $course);
        
        try {
            $statistics = $this->attendanceService->getCourseAttendanceStatistics($course->id);
            
            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve attendance statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance statistics for a student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function studentStatistics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($request->user_id);
        
        // Check if the user is authorized to view this student's attendance statistics
        $this->authorize('viewStudentAttendance', $user);
        
        try {
            $statistics = $this->attendanceService->getStudentAttendanceStatistics(
                $user->id,
                $request->course_id
            );
            
            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve attendance statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Identify at-risk students based on attendance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function atRiskStudents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'threshold' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::findOrFail($request->course_id);
        
        // Check if the user is authorized to view at-risk students for this course
        $this->authorize('viewAtRiskStudents', $course);
        
        try {
            $threshold = $request->threshold ?? 75; // Default to 75% attendance rate
            
            $atRiskStudents = $this->attendanceService->identifyAtRiskStudents(
                $course->id,
                $threshold
            );
            
            return response()->json([
                'success' => true,
                'data' => [
                    'threshold' => $threshold,
                    'at_risk_students' => $atRiskStudents
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to identify at-risk students',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
