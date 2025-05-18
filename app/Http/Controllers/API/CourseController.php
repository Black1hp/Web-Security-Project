<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\CourseWaitlist;
use App\Models\FinancialRecord;
use App\Services\CourseRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    protected $courseRegistrationService;

    public function __construct(CourseRegistrationService $courseRegistrationService)
    {
        $this->courseRegistrationService = $courseRegistrationService;
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of courses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Course::with(['department', 'department.faculty']);
        
        // Filter by department
        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        // Filter by semester
        if ($request->has('semester')) {
            $query->where('semester', $request->semester);
        }
        
        // Filter by instructor
        if ($request->has('professor_id')) {
            $query->whereHas('professor', function($q) use ($request) {
                $q->where('users.id', $request->professor_id);
            });
        }
        
        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        // Filter by registration period
        if ($request->has('registration_open')) {
            $now = now();
            $query->where('is_active', true)
                  ->where('registration_start', '<=', $now)
                  ->where('registration_end', '>=', $now);
        }
        
        $courses = $query->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    /**
     * Store a newly created course in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Course::class);
        
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:courses',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'credits' => 'required|integer|min:1|max:6',
            'professor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'semester' => 'required|string|max:20',
            'capacity' => 'required|integer|min:1',
            'tuition_per_credit' => 'required|numeric|min:0',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start',
            'meeting_days' => 'nullable|string|max:20',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'syllabus' => 'nullable|string',
            'prerequisites' => 'nullable|array',
            'prerequisites.*.course_id' => 'required|exists:courses,id',
            'prerequisites.*.min_grade' => 'required|string|max:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $course = Course::create($request->except('prerequisites'));
            
            // Add prerequisites if provided
            if ($request->has('prerequisites') && is_array($request->prerequisites)) {
                foreach ($request->prerequisites as $prerequisite) {
                    $course->prerequisites()->attach($prerequisite['course_id'], [
                        'min_grade' => $prerequisite['min_grade']
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Course created successfully',
                'data' => $course->load('prerequisites', 'department')
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified course.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::with([
            'department', 
            'department.faculty', 
            'prerequisites', 
            'professor'
        ])->findOrFail($id);
        
        // Check if the current user is enrolled in this course
        $user = Auth::user();
        $isEnrolled = Enrollment::where('user_id', $user->id)
                               ->where('course_id', $course->id)
                               ->where('status', 'active')
                               ->exists();
        
        // Check if the user is on the waitlist
        $isWaitlisted = CourseWaitlist::where('user_id', $user->id)
                                     ->where('course_id', $course->id)
                                     ->exists();
        
        // Get waitlist position if applicable
        $waitlistPosition = null;
        if ($isWaitlisted) {
            $waitlistPosition = CourseWaitlist::where('user_id', $user->id)
                                            ->where('course_id', $course->id)
                                            ->first()
                                            ->position;
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'course' => $course,
                'is_enrolled' => $isEnrolled,
                'is_waitlisted' => $isWaitlisted,
                'waitlist_position' => $waitlistPosition,
                'is_full' => $course->isFull(),
                'is_registration_open' => $course->isRegistrationOpen(),
                'available_seats' => $course->capacity - $course->enrolled_count
            ]
        ]);
    }

    /**
     * Update the specified course in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        
        $this->authorize('update', $course);
        
        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|required|string|max:20|unique:courses,code,' . $id,
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'credits' => 'sometimes|required|integer|min:1|max:6',
            'professor_id' => 'sometimes|required|exists:users,id',
            'department_id' => 'sometimes|required|exists:departments,id',
            'semester' => 'sometimes|required|string|max:20',
            'capacity' => 'sometimes|required|integer|min:1',
            'is_active' => 'sometimes|required|boolean',
            'tuition_per_credit' => 'sometimes|required|numeric|min:0',
            'registration_start' => 'sometimes|required|date',
            'registration_end' => 'sometimes|required|date|after:registration_start',
            'meeting_days' => 'nullable|string|max:20',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'syllabus' => 'nullable|string',
            'prerequisites' => 'sometimes|array',
            'prerequisites.*.course_id' => 'required|exists:courses,id',
            'prerequisites.*.min_grade' => 'required|string|max:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $course->update($request->except('prerequisites'));
            
            // Update prerequisites if provided
            if ($request->has('prerequisites')) {
                // Remove existing prerequisites
                $course->prerequisites()->detach();
                
                // Add new prerequisites
                foreach ($request->prerequisites as $prerequisite) {
                    $course->prerequisites()->attach($prerequisite['course_id'], [
                        'min_grade' => $prerequisite['min_grade']
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully',
                'data' => $course->load('prerequisites', 'department')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified course from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        
        $this->authorize('delete', $course);
        
        // Check if there are active enrollments
        $hasEnrollments = Enrollment::where('course_id', $id)
                                   ->where('status', 'active')
                                   ->exists();
        
        if ($hasEnrollments) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete course with active enrollments'
            ], 422);
        }
        
        try {
            $course->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register a student for a course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, $id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);
        
        try {
            $result = $this->courseRegistrationService->registerForCourse($user, $course);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => $result['data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 422);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register for course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Drop a course.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function drop($id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);
        
        try {
            $result = $this->courseRegistrationService->dropCourse($user, $course);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => $result['data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 422);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to drop course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Join the waitlist for a course.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function joinWaitlist($id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);
        
        try {
            $result = $this->courseRegistrationService->joinWaitlist($user, $course);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => $result['data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 422);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to join waitlist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Leave the waitlist for a course.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leaveWaitlist($id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);
        
        try {
            $result = $this->courseRegistrationService->leaveWaitlist($user, $course);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 422);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to leave waitlist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get student's course schedule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function schedule(Request $request)
    {
        $user = Auth::user();
        $semester = $request->query('semester', null);
        
        $query = Enrollment::with(['course' => function($q) {
                    $q->with('department');
                }])
                ->where('user_id', $user->id)
                ->where('status', 'active');
        
        if ($semester) {
            $query->whereHas('course', function($q) use ($semester) {
                $q->where('semester', $semester);
            });
        }
        
        $enrollments = $query->get();
        
        // Format the schedule data
        $schedule = [];
        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            
            $schedule[] = [
                'id' => $course->id,
                'code' => $course->code,
                'title' => $course->title,
                'credits' => $course->credits,
                'department' => $course->department->name,
                'meeting_days' => $course->meeting_days,
                'start_time' => $course->start_time,
                'end_time' => $course->end_time,
                'location' => $course->location,
                'professor' => $course->professor->first()->name ?? 'TBA',
                'enrollment_date' => $enrollment->enrollment_date,
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    /**
     * Check for schedule conflicts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkConflicts(Request $request)
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

        $user = Auth::user();
        $courseId = $request->course_id;
        
        try {
            $conflicts = $this->courseRegistrationService->checkScheduleConflicts($user, $courseId);
            
            return response()->json([
                'success' => true,
                'has_conflicts' => count($conflicts) > 0,
                'conflicts' => $conflicts
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check schedule conflicts',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
