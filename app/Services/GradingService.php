<?php

namespace App\Services;

use App\Models\User;
use App\Models\Grade;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\AcademicRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GradingService
{
    /**
     * Calculate GPA for a user for a specific semester.
     *
     * @param  int  $userId
     * @param  string  $semester
     * @return array
     */
    public function calculateSemesterGPA($userId, $semester)
    {
        // Get all completed enrollments for the user in the specified semester
        $enrollments = Enrollment::where('user_id', $userId)
                               ->where('status', 'completed')
                               ->whereHas('course', function($query) use ($semester) {
                                   $query->where('semester', $semester);
                               })
                               ->with(['course', 'grade'])
                               ->get();
        
        $totalPoints = 0;
        $totalCredits = 0;
        $creditsEarned = 0;
        
        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            $grade = $enrollment->grade;
            
            if (!$grade) {
                continue;
            }
            
            $credits = $course->credits;
            $points = $grade->points;
            
            $totalPoints += ($points * $credits);
            $totalCredits += $credits;
            
            // Credits are earned if the grade is passing (D or better)
            if ($points >= 1.0) {
                $creditsEarned += $credits;
            }
        }
        
        $semesterGPA = ($totalCredits > 0) ? ($totalPoints / $totalCredits) : 0;
        
        return [
            'semester_gpa' => round($semesterGPA, 2),
            'credits_attempted' => $totalCredits,
            'credits_earned' => $creditsEarned
        ];
    }
    
    /**
     * Calculate cumulative GPA for a user.
     *
     * @param  int  $userId
     * @return array
     */
    public function calculateCumulativeGPA($userId)
    {
        // Get all completed enrollments for the user
        $enrollments = Enrollment::where('user_id', $userId)
                               ->where('status', 'completed')
                               ->with(['course', 'grade'])
                               ->get();
        
        $totalPoints = 0;
        $totalCredits = 0;
        $totalCreditsEarned = 0;
        
        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            $grade = $enrollment->grade;
            
            if (!$grade) {
                continue;
            }
            
            $credits = $course->credits;
            $points = $grade->points;
            
            $totalPoints += ($points * $credits);
            $totalCredits += $credits;
            
            // Credits are earned if the grade is passing (D or better)
            if ($points >= 1.0) {
                $totalCreditsEarned += $credits;
            }
        }
        
        $cumulativeGPA = ($totalCredits > 0) ? ($totalPoints / $totalCredits) : 0;
        
        return [
            'cumulative_gpa' => round($cumulativeGPA, 2),
            'total_credits_attempted' => $totalCredits,
            'total_credits_earned' => $totalCreditsEarned
        ];
    }
    
    /**
     * Update the academic record for a user for a specific semester.
     *
     * @param  int  $userId
     * @param  string  $semester
     * @return \App\Models\AcademicRecord
     */
    public function updateAcademicRecord($userId, $semester)
    {
        // Calculate semester GPA
        $semesterGPA = $this->calculateSemesterGPA($userId, $semester);
        
        // Calculate cumulative GPA
        $cumulativeGPA = $this->calculateCumulativeGPA($userId);
        
        // Determine academic standing
        $academicStanding = AcademicRecord::calculateAcademicStanding($cumulativeGPA['cumulative_gpa']);
        
        // Check for dean's list and honors
        $deansList = AcademicRecord::qualifiesForDeansList($semesterGPA['semester_gpa'], $semesterGPA['credits_earned']);
        $honors = AcademicRecord::qualifiesForHonors($cumulativeGPA['cumulative_gpa']);
        
        // Update or create the academic record
        $record = AcademicRecord::updateOrCreate(
            [
                'user_id' => $userId,
                'semester' => $semester
            ],
            [
                'semester_gpa' => $semesterGPA['semester_gpa'],
                'cumulative_gpa' => $cumulativeGPA['cumulative_gpa'],
                'credits_attempted' => $semesterGPA['credits_attempted'],
                'credits_earned' => $semesterGPA['credits_earned'],
                'academic_standing' => $academicStanding,
                'deans_list' => $deansList,
                'honors' => $honors,
                'updated_by' => auth()->id()
            ]
        );
        
        return $record;
    }
    
    /**
     * Generate a transcript for a user.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function generateTranscript(User $user)
    {
        // Get all academic records for the user
        $academicRecords = AcademicRecord::where('user_id', $user->id)
                                       ->orderBy('semester', 'desc')
                                       ->get();
        
        // Get all completed enrollments with grades
        $enrollments = Enrollment::where('user_id', $user->id)
                               ->where('status', 'completed')
                               ->with(['course', 'course.department', 'grade'])
                               ->get();
        
        // Group enrollments by semester
        $semesterCourses = [];
        foreach ($enrollments as $enrollment) {
            $semester = $enrollment->course->semester;
            
            if (!isset($semesterCourses[$semester])) {
                $semesterCourses[$semester] = [];
            }
            
            $semesterCourses[$semester][] = [
                'course_code' => $enrollment->course->code,
                'course_title' => $enrollment->course->title,
                'department' => $enrollment->course->department->name,
                'credits' => $enrollment->course->credits,
                'grade' => $enrollment->grade ? $enrollment->grade->grade : 'N/A',
                'points' => $enrollment->grade ? $enrollment->grade->points : 0,
            ];
        }
        
        // Format academic records
        $formattedRecords = [];
        foreach ($academicRecords as $record) {
            $semester = $record->semester;
            
            $formattedRecords[$semester] = [
                'semester' => $semester,
                'semester_gpa' => $record->semester_gpa,
                'cumulative_gpa' => $record->cumulative_gpa,
                'credits_attempted' => $record->credits_attempted,
                'credits_earned' => $record->credits_earned,
                'academic_standing' => $record->academic_standing,
                'deans_list' => $record->deans_list,
                'honors' => $record->honors,
                'courses' => $semesterCourses[$semester] ?? []
            ];
        }
        
        // Get the latest academic record for overall summary
        $latestRecord = $academicRecords->first();
        
        return [
            'student' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'summary' => $latestRecord ? [
                'cumulative_gpa' => $latestRecord->cumulative_gpa,
                'total_credits_earned' => $latestRecord->credits_earned,
                'academic_standing' => $latestRecord->academic_standing,
                'deans_list' => $latestRecord->deans_list,
                'honors' => $latestRecord->honors
            ] : null,
            'semesters' => $formattedRecords,
            'generated_at' => now()->toDateTimeString(),
        ];
    }
    
    /**
     * Calculate grade distribution for a course.
     *
     * @param  \App\Models\Course  $course
     * @return array
     */
    public function calculateGradeDistribution(Course $course)
    {
        // Get all grades for the course
        $grades = Grade::whereHas('enrollment', function($query) use ($course) {
                      $query->where('course_id', $course->id);
                  })->get();
        
        // Initialize distribution array
        $distribution = [
            'A+' => 0, 'A' => 0, 'A-' => 0,
            'B+' => 0, 'B' => 0, 'B-' => 0,
            'C+' => 0, 'C' => 0, 'C-' => 0,
            'D+' => 0, 'D' => 0, 'D-' => 0,
            'F' => 0
        ];
        
        // Count grades
        foreach ($grades as $grade) {
            if (isset($distribution[$grade->grade])) {
                $distribution[$grade->grade]++;
            }
        }
        
        // Calculate percentages
        $total = $grades->count();
        $percentages = [];
        
        foreach ($distribution as $grade => $count) {
            $percentages[$grade] = $total > 0 ? round(($count / $total) * 100, 2) : 0;
        }
        
        // Calculate average GPA
        $totalPoints = $grades->sum('points');
        $averageGPA = $total > 0 ? round($totalPoints / $total, 2) : 0;
        
        return [
            'course_id' => $course->id,
            'course_code' => $course->code,
            'course_title' => $course->title,
            'total_students' => $total,
            'distribution' => $distribution,
            'percentages' => $percentages,
            'average_gpa' => $averageGPA
        ];
    }
    
    /**
     * Check if a student meets graduation requirements.
     *
     * @param  \App\Models\User  $user
     * @param  string  $program
     * @return array
     */
    public function checkGraduationRequirements(User $user, $program)
    {
        // This is a simplified version. In a real system, this would be much more complex
        // and would check specific program requirements, core courses, electives, etc.
        
        // Get the latest academic record
        $latestRecord = AcademicRecord::where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();
        
        if (!$latestRecord) {
            return [
                'meets_requirements' => false,
                'reason' => 'No academic records found.'
            ];
        }
        
        // Define program requirements (simplified)
        $requirements = [
            'undergraduate' => [
                'min_credits' => 120,
                'min_gpa' => 2.0
            ],
            'graduate' => [
                'min_credits' => 36,
                'min_gpa' => 3.0
            ],
            'phd' => [
                'min_credits' => 72,
                'min_gpa' => 3.5
            ]
        ];
        
        if (!isset($requirements[$program])) {
            return [
                'meets_requirements' => false,
                'reason' => 'Invalid program specified.'
            ];
        }
        
        $programReqs = $requirements[$program];
        $meetsCredits = $latestRecord->credits_earned >= $programReqs['min_credits'];
        $meetsGPA = $latestRecord->cumulative_gpa >= $programReqs['min_gpa'];
        
        $missingRequirements = [];
        
        if (!$meetsCredits) {
            $missingRequirements[] = [
                'requirement' => 'minimum_credits',
                'required' => $programReqs['min_credits'],
                'actual' => $latestRecord->credits_earned,
                'remaining' => $programReqs['min_credits'] - $latestRecord->credits_earned
            ];
        }
        
        if (!$meetsGPA) {
            $missingRequirements[] = [
                'requirement' => 'minimum_gpa',
                'required' => $programReqs['min_gpa'],
                'actual' => $latestRecord->cumulative_gpa
            ];
        }
        
        return [
            'meets_requirements' => $meetsCredits && $meetsGPA,
            'program' => $program,
            'credits_earned' => $latestRecord->credits_earned,
            'cumulative_gpa' => $latestRecord->cumulative_gpa,
            'missing_requirements' => $missingRequirements
        ];
    }
}
