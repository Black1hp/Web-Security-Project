<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\AcademicRecord;
use App\Services\GradingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GradeController extends Controller
{
    protected $gradingService;

    public function __construct(GradingService $gradingService)
    {
        $this->gradingService = $gradingService;
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of grades for the authenticated user or for a specific course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check if the user is a student or an instructor
        $isInstructor = $user->hasRole('instructor') || $user->hasRole('admin');
        
        if ($isInstructor && $request->has('course_id')) {
            // Instructor viewing grades for a specific course
            $course = Course::findOrFail($request->course_id);
            
            // Check if the user is authorized to view grades for this course
            $this->authorize('viewGrades', $course);
            
            $grades = Grade::whereHas('enrollment', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })->with(['enrollment.user'])->get();
            
            return response()->json([
                'success' => true,
                'data' => $grades
            ]);
        } else {
            // Student viewing their own grades
            $grades = Grade::whereHas('enrollment', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['enrollment.course'])->get();
            
            return response()->json([
                'success' => true,
                'data' => $grades
            ]);
        }
    }

    /**
     * Store a newly created grade in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enrollment_id' => 'required|exists:enrollments,id',
            'grade' => 'required|string|max:2',
            'points' => 'required|numeric|min:0|max:4.3',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $enrollment = Enrollment::findOrFail($request->enrollment_id);
        
        // Check if the user is authorized to create grades for this enrollment
        $this->authorize('createGrade', $enrollment);
        
        // Check if a grade already exists for this enrollment
        $existingGrade = Grade::where('enrollment_id', $enrollment->id)->first();
        
        if ($existingGrade) {
            return response()->json([
                'success' => false,
                'message' => 'A grade already exists for this enrollment. Please use the update method instead.'
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $grade = Grade::create($request->all());
            
            // Update the enrollment status to completed
            $enrollment->status = 'completed';
            $enrollment->save();
            
            // Update the academic record for the student
            $this->gradingService->updateAcademicRecord($enrollment->user_id, $enrollment->course->semester);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Grade created successfully',
                'data' => $grade
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create grade',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified grade.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $grade = Grade::with(['enrollment.user', 'enrollment.course'])->findOrFail($id);
        
        // Check if the user is authorized to view this grade
        $this->authorize('view', $grade);
        
        return response()->json([
            'success' => true,
            'data' => $grade
        ]);
    }

    /**
     * Update the specified grade in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $grade = Grade::findOrFail($id);
        
        // Check if the user is authorized to update this grade
        $this->authorize('update', $grade);
        
        $validator = Validator::make($request->all(), [
            'grade' => 'sometimes|required|string|max:2',
            'points' => 'sometimes|required|numeric|min:0|max:4.3',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $grade->update($request->all());
            
            // Update the academic record for the student
            $enrollment = $grade->enrollment;
            $this->gradingService->updateAcademicRecord($enrollment->user_id, $enrollment->course->semester);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Grade updated successfully',
                'data' => $grade
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update grade',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified grade from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);
        
        // Check if the user is authorized to delete this grade
        $this->authorize('delete', $grade);
        
        try {
            DB::beginTransaction();
            
            $enrollment = $grade->enrollment;
            $userId = $enrollment->user_id;
            $semester = $enrollment->course->semester;
            
            $grade->delete();
            
            // Update the enrollment status back to active
            $enrollment->status = 'active';
            $enrollment->save();
            
            // Update the academic record for the student
            $this->gradingService->updateAcademicRecord($userId, $semester);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Grade deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete grade',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit grades for multiple students in a course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'grades' => 'required|array',
            'grades.*.enrollment_id' => 'required|exists:enrollments,id',
            'grades.*.grade' => 'required|string|max:2',
            'grades.*.points' => 'required|numeric|min:0|max:4.3',
            'grades.*.remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::findOrFail($request->course_id);
        
        // Check if the user is authorized to submit grades for this course
        $this->authorize('submitGrades', $course);
        
        try {
            DB::beginTransaction();
            
            $results = [];
            $updatedStudents = [];
            
            foreach ($request->grades as $gradeData) {
                $enrollment = Enrollment::findOrFail($gradeData['enrollment_id']);
                
                // Verify that the enrollment is for the specified course
                if ($enrollment->course_id != $course->id) {
                    DB::rollBack();
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Enrollment ID ' . $enrollment->id . ' is not for the specified course.'
                    ], 422);
                }
                
                // Check if a grade already exists for this enrollment
                $existingGrade = Grade::where('enrollment_id', $enrollment->id)->first();
                
                if ($existingGrade) {
                    // Update existing grade
                    $existingGrade->update([
                        'grade' => $gradeData['grade'],
                        'points' => $gradeData['points'],
                        'remarks' => $gradeData['remarks'] ?? null,
                    ]);
                    
                    $results[] = [
                        'enrollment_id' => $enrollment->id,
                        'status' => 'updated',
                        'grade' => $existingGrade
                    ];
                } else {
                    // Create new grade
                    $grade = Grade::create([
                        'enrollment_id' => $enrollment->id,
                        'grade' => $gradeData['grade'],
                        'points' => $gradeData['points'],
                        'remarks' => $gradeData['remarks'] ?? null,
                    ]);
                    
                    // Update the enrollment status to completed
                    $enrollment->status = 'completed';
                    $enrollment->save();
                    
                    $results[] = [
                        'enrollment_id' => $enrollment->id,
                        'status' => 'created',
                        'grade' => $grade
                    ];
                }
                
                // Keep track of students whose academic records need to be updated
                if (!in_array($enrollment->user_id, $updatedStudents)) {
                    $updatedStudents[] = $enrollment->user_id;
                }
            }
            
            // Update academic records for all affected students
            foreach ($updatedStudents as $userId) {
                $this->gradingService->updateAcademicRecord($userId, $course->semester);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Grades submitted successfully',
                'data' => $results
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit grades',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a transcript for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateTranscript()
    {
        $user = Auth::user();
        
        try {
            $transcript = $this->gradingService->generateTranscript($user);
            
            return response()->json([
                'success' => true,
                'data' => $transcript
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate transcript',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the GPA for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getGPA(Request $request)
    {
        $user = Auth::user();
        $semester = $request->query('semester');
        
        try {
            if ($semester) {
                // Get semester GPA
                $record = AcademicRecord::where('user_id', $user->id)
                                      ->where('semester', $semester)
                                      ->first();
                
                if (!$record) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No academic record found for the specified semester.'
                    ], 404);
                }
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'semester' => $semester,
                        'semester_gpa' => $record->semester_gpa,
                        'cumulative_gpa' => $record->cumulative_gpa,
                        'credits_attempted' => $record->credits_attempted,
                        'credits_earned' => $record->credits_earned,
                        'academic_standing' => $record->academic_standing,
                        'deans_list' => $record->deans_list,
                        'honors' => $record->honors
                    ]
                ]);
            } else {
                // Get cumulative GPA
                $latestRecord = AcademicRecord::where('user_id', $user->id)
                                            ->orderBy('created_at', 'desc')
                                            ->first();
                
                if (!$latestRecord) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No academic records found.'
                    ], 404);
                }
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'cumulative_gpa' => $latestRecord->cumulative_gpa,
                        'total_credits_earned' => $latestRecord->credits_earned,
                        'academic_standing' => $latestRecord->academic_standing,
                        'deans_list' => $latestRecord->deans_list,
                        'honors' => $latestRecord->honors
                    ]
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve GPA',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
