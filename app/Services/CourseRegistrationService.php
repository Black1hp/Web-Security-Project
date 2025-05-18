<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CoursePrerequisite;
use App\Models\CourseWaitlist;
use App\Models\Enrollment;
use App\Models\FinancialRecord;
use App\Models\User;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CourseRegistrationService
{
    /**
     * Register a user for a course.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return array
     */
    public function registerForCourse(User $user, Course $course)
    {
        // Check if registration is open
        if (!$course->isRegistrationOpen()) {
            return [
                'success' => false,
                'message' => 'Course registration is not open at this time.'
            ];
        }
        
        // Check if the user is already enrolled
        $existingEnrollment = Enrollment::where('user_id', $user->id)
                                      ->where('course_id', $course->id)
                                      ->where('status', 'active')
                                      ->first();
        
        if ($existingEnrollment) {
            return [
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ];
        }
        
        // Check if the course is full
        if ($course->isFull()) {
            // Offer to join the waitlist
            return [
                'success' => false,
                'message' => 'This course is full. Would you like to join the waitlist?',
                'can_waitlist' => true
            ];
        }
        
        // Check for schedule conflicts
        $conflicts = $this->checkScheduleConflicts($user, $course->id);
        if (count($conflicts) > 0) {
            return [
                'success' => false,
                'message' => 'You have a schedule conflict with this course.',
                'conflicts' => $conflicts
            ];
        }
        
        // Check if the user has met the prerequisites
        $prerequisiteCheck = $this->checkPrerequisites($user, $course);
        if (!$prerequisiteCheck['success']) {
            return [
                'success' => false,
                'message' => $prerequisiteCheck['message'],
                'missing_prerequisites' => $prerequisiteCheck['missing_prerequisites']
            ];
        }
        
        // Check for financial holds
        $hasFinancialHold = FinancialRecord::where('user_id', $user->id)
                                         ->where('status', 'pending')
                                         ->where('due_date', '<', now())
                                         ->exists();
        
        if ($hasFinancialHold) {
            return [
                'success' => false,
                'message' => 'You have a financial hold on your account. Please resolve any outstanding balances before registering for courses.'
            ];
        }
        
        try {
            DB::beginTransaction();
            
            // Create the enrollment
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'enrollment_date' => now(),
                'status' => 'active',
            ]);
            
            // Increment the enrolled count
            $course->increment('enrolled_count');
            
            // Create a financial record for the tuition
            $tuition = $course->calculateTuition();
            $financialRecord = FinancialRecord::create([
                'user_id' => $user->id,
                'transaction_type' => 'tuition',
                'reference_type' => 'App\Models\Enrollment',
                'reference_id' => $enrollment->id,
                'amount' => $tuition,
                'status' => 'pending',
                'description' => 'Tuition for ' . $course->code . ' - ' . $course->title,
                'due_date' => now()->addDays(30),
            ]);
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Successfully registered for the course.',
                'data' => [
                    'enrollment' => $enrollment,
                    'financial_record' => $financialRecord
                ]
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Failed to register for the course: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Drop a course.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return array
     */
    public function dropCourse(User $user, Course $course)
    {
        // Check if the user is enrolled in the course
        $enrollment = Enrollment::where('user_id', $user->id)
                              ->where('course_id', $course->id)
                              ->where('status', 'active')
                              ->first();
        
        if (!$enrollment) {
            return [
                'success' => false,
                'message' => 'You are not enrolled in this course.'
            ];
        }
        
        // Check if it's within the drop period
        $now = now();
        $isWithinDropPeriod = $now <= $course->registration_end->addDays(14); // Assuming 2-week drop period after registration ends
        
        try {
            DB::beginTransaction();
            
            // Update the enrollment status
            $enrollment->status = 'dropped';
            $enrollment->save();
            
            // Decrement the enrolled count
            $course->decrement('enrolled_count');
            
            // Process refund if within drop period
            if ($isWithinDropPeriod) {
                // Find the tuition record
                $tuitionRecord = FinancialRecord::where('user_id', $user->id)
                                              ->where('transaction_type', 'tuition')
                                              ->where('reference_type', 'App\Models\Enrollment')
                                              ->where('reference_id', $enrollment->id)
                                              ->first();
                
                if ($tuitionRecord) {
                    if ($tuitionRecord->status === 'pending') {
                        // If tuition hasn't been paid, just cancel it
                        $tuitionRecord->status = 'cancelled';
                        $tuitionRecord->save();
                    } else if ($tuitionRecord->status === 'completed') {
                        // If tuition has been paid, create a refund
                        $refundAmount = $tuitionRecord->amount;
                        
                        FinancialRecord::create([
                            'user_id' => $user->id,
                            'transaction_type' => 'refund',
                            'reference_type' => 'App\Models\Enrollment',
                            'reference_id' => $enrollment->id,
                            'amount' => $refundAmount,
                            'status' => 'pending',
                            'description' => 'Refund for dropping ' . $course->code . ' - ' . $course->title,
                        ]);
                    }
                }
            }
            
            // Check if there are students on the waitlist
            $waitlistEntry = CourseWaitlist::where('course_id', $course->id)
                                         ->orderBy('position')
                                         ->first();
            
            if ($waitlistEntry) {
                // Notify the next student on the waitlist (in a real system, this would send an email)
                // For now, we'll just include this information in the response
                $nextStudent = User::find($waitlistEntry->user_id);
                
                $waitlistNotification = [
                    'student_id' => $nextStudent->id,
                    'student_name' => $nextStudent->name,
                    'student_email' => $nextStudent->email,
                    'message' => 'A spot has opened up in ' . $course->code . ' - ' . $course->title . '. You have 48 hours to register.'
                ];
            }
            
            DB::commit();
            
            $response = [
                'success' => true,
                'message' => 'Successfully dropped the course.',
                'data' => [
                    'enrollment' => $enrollment,
                    'refund_processed' => $isWithinDropPeriod
                ]
            ];
            
            if (isset($waitlistNotification)) {
                $response['data']['waitlist_notification'] = $waitlistNotification;
            }
            
            return $response;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Failed to drop the course: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Join the waitlist for a course.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return array
     */
    public function joinWaitlist(User $user, Course $course)
    {
        // Check if the user is already enrolled
        $isEnrolled = Enrollment::where('user_id', $user->id)
                              ->where('course_id', $course->id)
                              ->where('status', 'active')
                              ->exists();
        
        if ($isEnrolled) {
            return [
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ];
        }
        
        // Check if the user is already on the waitlist
        $isWaitlisted = CourseWaitlist::where('user_id', $user->id)
                                    ->where('course_id', $course->id)
                                    ->exists();
        
        if ($isWaitlisted) {
            return [
                'success' => false,
                'message' => 'You are already on the waitlist for this course.'
            ];
        }
        
        // Check if the course is actually full
        if (!$course->isFull()) {
            return [
                'success' => false,
                'message' => 'This course is not full. You can register directly.'
            ];
        }
        
        try {
            // Get the next position in the waitlist
            $lastPosition = CourseWaitlist::where('course_id', $course->id)
                                        ->max('position') ?? 0;
            
            $waitlistEntry = CourseWaitlist::create([
                'course_id' => $course->id,
                'user_id' => $user->id,
                'position' => $lastPosition + 1,
                'joined_at' => now(),
            ]);
            
            return [
                'success' => true,
                'message' => 'Successfully joined the waitlist for this course.',
                'data' => [
                    'waitlist_entry' => $waitlistEntry,
                    'position' => $waitlistEntry->position
                ]
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to join the waitlist: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Leave the waitlist for a course.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return array
     */
    public function leaveWaitlist(User $user, Course $course)
    {
        // Check if the user is on the waitlist
        $waitlistEntry = CourseWaitlist::where('user_id', $user->id)
                                     ->where('course_id', $course->id)
                                     ->first();
        
        if (!$waitlistEntry) {
            return [
                'success' => false,
                'message' => 'You are not on the waitlist for this course.'
            ];
        }
        
        try {
            DB::beginTransaction();
            
            $position = $waitlistEntry->position;
            
            // Delete the waitlist entry
            $waitlistEntry->delete();
            
            // Update positions for students behind this one
            CourseWaitlist::where('course_id', $course->id)
                        ->where('position', '>', $position)
                        ->decrement('position');
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Successfully left the waitlist for this course.'
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Failed to leave the waitlist: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Check if a user has met the prerequisites for a course.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return array
     */
    public function checkPrerequisites(User $user, Course $course)
    {
        $prerequisites = $course->prerequisites;
        
        if ($prerequisites->isEmpty()) {
            return [
                'success' => true,
                'message' => 'No prerequisites required.'
            ];
        }
        
        $missingPrerequisites = [];
        
        foreach ($prerequisites as $prerequisite) {
            $pivot = $prerequisite->pivot;
            $minGrade = $pivot->min_grade;
            
            // Check if the user has completed the prerequisite course
            $enrollment = Enrollment::where('user_id', $user->id)
                                  ->where('course_id', $prerequisite->id)
                                  ->where('status', 'completed')
                                  ->first();
            
            if (!$enrollment) {
                $missingPrerequisites[] = [
                    'course_id' => $prerequisite->id,
                    'course_code' => $prerequisite->code,
                    'course_title' => $prerequisite->title,
                    'reason' => 'Not completed'
                ];
                continue;
            }
            
            // Check if the user has achieved the minimum grade
            $grade = Grade::where('enrollment_id', $enrollment->id)->first();
            
            if (!$grade) {
                $missingPrerequisites[] = [
                    'course_id' => $prerequisite->id,
                    'course_code' => $prerequisite->code,
                    'course_title' => $prerequisite->title,
                    'reason' => 'No grade recorded'
                ];
                continue;
            }
            
            // Convert letter grade to a numeric value for comparison
            $gradeValues = [
                'A+' => 4.3, 'A' => 4.0, 'A-' => 3.7,
                'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7,
                'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7,
                'D+' => 1.3, 'D' => 1.0, 'D-' => 0.7,
                'F' => 0.0
            ];
            
            $studentGradeValue = $gradeValues[$grade->grade] ?? 0;
            $minGradeValue = $gradeValues[$minGrade] ?? 0;
            
            if ($studentGradeValue < $minGradeValue) {
                $missingPrerequisites[] = [
                    'course_id' => $prerequisite->id,
                    'course_code' => $prerequisite->code,
                    'course_title' => $prerequisite->title,
                    'reason' => 'Grade ' . $grade->grade . ' is below the required minimum of ' . $minGrade
                ];
            }
        }
        
        if (count($missingPrerequisites) > 0) {
            return [
                'success' => false,
                'message' => 'You have not met all the prerequisites for this course.',
                'missing_prerequisites' => $missingPrerequisites
            ];
        }
        
        return [
            'success' => true,
            'message' => 'All prerequisites have been met.'
        ];
    }
    
    /**
     * Check for schedule conflicts.
     *
     * @param  \App\Models\User  $user
     * @param  int  $courseId
     * @return array
     */
    public function checkScheduleConflicts(User $user, $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // If the course doesn't have a schedule, there can't be conflicts
        if (!$course->meeting_days || !$course->start_time || !$course->end_time) {
            return [];
        }
        
        $courseDays = str_split($course->meeting_days);
        $courseStart = Carbon::parse($course->start_time);
        $courseEnd = Carbon::parse($course->end_time);
        
        // Get all active enrollments for the user in the same semester
        $enrollments = Enrollment::with('course')
                                ->where('user_id', $user->id)
                                ->where('status', 'active')
                                ->whereHas('course', function($query) use ($course) {
                                    $query->where('semester', $course->semester);
                                })
                                ->get();
        
        $conflicts = [];
        
        foreach ($enrollments as $enrollment) {
            $enrolledCourse = $enrollment->course;
            
            // Skip if this is the same course or if the enrolled course doesn't have a schedule
            if ($enrolledCourse->id == $courseId || 
                !$enrolledCourse->meeting_days || 
                !$enrolledCourse->start_time || 
                !$enrolledCourse->end_time) {
                continue;
            }
            
            $enrolledDays = str_split($enrolledCourse->meeting_days);
            $enrolledStart = Carbon::parse($enrolledCourse->start_time);
            $enrolledEnd = Carbon::parse($enrolledCourse->end_time);
            
            // Check for day overlap
            $dayOverlap = false;
            foreach ($courseDays as $day) {
                if (in_array($day, $enrolledDays)) {
                    $dayOverlap = true;
                    break;
                }
            }
            
            if (!$dayOverlap) {
                continue;
            }
            
            // Check for time overlap
            $timeOverlap = ($courseStart < $enrolledEnd && $courseEnd > $enrolledStart);
            
            if ($timeOverlap) {
                $conflicts[] = [
                    'course_id' => $enrolledCourse->id,
                    'course_code' => $enrolledCourse->code,
                    'course_title' => $enrolledCourse->title,
                    'meeting_days' => $enrolledCourse->meeting_days,
                    'start_time' => $enrolledCourse->start_time,
                    'end_time' => $enrolledCourse->end_time,
                    'location' => $enrolledCourse->location
                ];
            }
        }
        
        return $conflicts;
    }
    
    /**
     * Process students from the waitlist when a spot opens up.
     *
     * @param  \App\Models\Course  $course
     * @return array
     */
    public function processWaitlist(Course $course)
    {
        // Check if the course has available spots
        if ($course->isFull()) {
            return [
                'success' => false,
                'message' => 'No available spots in the course.'
            ];
        }
        
        // Get the next student on the waitlist
        $waitlistEntry = CourseWaitlist::where('course_id', $course->id)
                                     ->orderBy('position')
                                     ->first();
        
        if (!$waitlistEntry) {
            return [
                'success' => false,
                'message' => 'No students on the waitlist.'
            ];
        }
        
        $user = User::find($waitlistEntry->user_id);
        
        // In a real system, we would send a notification to the student
        // For now, we'll just return the information
        
        return [
            'success' => true,
            'message' => 'Student notified of available spot.',
            'data' => [
                'student_id' => $user->id,
                'student_name' => $user->name,
                'student_email' => $user->email,
                'waitlist_entry' => $waitlistEntry,
                'notification_message' => 'A spot has opened up in ' . $course->code . ' - ' . $course->title . '. You have 48 hours to register.'
            ]
        ];
    }
}
