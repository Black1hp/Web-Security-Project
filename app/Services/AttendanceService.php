<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\StudentAttendance;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Get attendance statistics for a course.
     *
     * @param  int  $courseId
     * @return array
     */
    public function getCourseAttendanceStatistics($courseId)
    {
        // Get all attendance records for the course
        $attendanceRecords = AttendanceRecord::where('course_id', $courseId)->get();
        
        if ($attendanceRecords->isEmpty()) {
            return [
                'course_id' => $courseId,
                'total_sessions' => 0,
                'average_attendance_rate' => 0,
                'status_distribution' => [
                    'present' => 0,
                    'absent' => 0,
                    'late' => 0,
                    'excused' => 0
                ],
                'sessions' => []
            ];
        }
        
        $totalSessions = $attendanceRecords->count();
        $sessionStats = [];
        
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalLate = 0;
        $totalExcused = 0;
        $totalStudents = 0;
        
        foreach ($attendanceRecords as $record) {
            $studentAttendances = StudentAttendance::where('attendance_record_id', $record->id)->get();
            
            $presentCount = $studentAttendances->where('status', 'present')->count();
            $absentCount = $studentAttendances->where('status', 'absent')->count();
            $lateCount = $studentAttendances->where('status', 'late')->count();
            $excusedCount = $studentAttendances->where('status', 'excused')->count();
            $totalCount = $studentAttendances->count();
            
            $totalPresent += $presentCount;
            $totalAbsent += $absentCount;
            $totalLate += $lateCount;
            $totalExcused += $excusedCount;
            $totalStudents += $totalCount;
            
            $attendanceRate = ($totalCount > 0) ? 
                (($presentCount + $lateCount + $excusedCount) / $totalCount) * 100 : 0;
            
            $sessionStats[] = [
                'id' => $record->id,
                'session_date' => $record->session_date,
                'start_time' => $record->start_time,
                'end_time' => $record->end_time,
                'topic' => $record->topic,
                'attendance_rate' => round($attendanceRate, 2),
                'present_count' => $presentCount,
                'absent_count' => $absentCount,
                'late_count' => $lateCount,
                'excused_count' => $excusedCount,
                'total_students' => $totalCount
            ];
        }
        
        // Calculate overall attendance rate
        $overallAttendanceRate = ($totalStudents > 0) ? 
            (($totalPresent + $totalLate + $totalExcused) / $totalStudents) * 100 : 0;
        
        return [
            'course_id' => $courseId,
            'total_sessions' => $totalSessions,
            'average_attendance_rate' => round($overallAttendanceRate, 2),
            'status_distribution' => [
                'present' => $totalPresent,
                'absent' => $totalAbsent,
                'late' => $totalLate,
                'excused' => $totalExcused
            ],
            'sessions' => $sessionStats
        ];
    }
    
    /**
     * Get attendance statistics for a student.
     *
     * @param  int  $userId
     * @param  int|null  $courseId
     * @return array
     */
    public function getStudentAttendanceStatistics($userId, $courseId = null)
    {
        $query = StudentAttendance::where('user_id', $userId);
        
        if ($courseId) {
            $query->whereHas('attendanceRecord', function($q) use ($courseId) {
                $q->where('course_id', $courseId);
            });
        }
        
        $studentAttendances = $query->with('attendanceRecord')->get();
        
        if ($studentAttendances->isEmpty()) {
            return [
                'user_id' => $userId,
                'course_id' => $courseId,
                'total_sessions' => 0,
                'attendance_rate' => 0,
                'status_counts' => [
                    'present' => 0,
                    'absent' => 0,
                    'late' => 0,
                    'excused' => 0
                ],
                'sessions' => []
            ];
        }
        
        $presentCount = $studentAttendances->where('status', 'present')->count();
        $absentCount = $studentAttendances->where('status', 'absent')->count();
        $lateCount = $studentAttendances->where('status', 'late')->count();
        $excusedCount = $studentAttendances->where('status', 'excused')->count();
        $totalSessions = $studentAttendances->count();
        
        $attendanceRate = ($totalSessions > 0) ? 
            (($presentCount + $lateCount + $excusedCount) / $totalSessions) * 100 : 0;
        
        $sessionDetails = [];
        
        foreach ($studentAttendances as $attendance) {
            $record = $attendance->attendanceRecord;
            
            $sessionDetails[] = [
                'attendance_id' => $attendance->id,
                'record_id' => $record->id,
                'course_id' => $record->course_id,
                'session_date' => $record->session_date,
                'start_time' => $record->start_time,
                'end_time' => $record->end_time,
                'topic' => $record->topic,
                'status' => $attendance->status,
                'check_in_time' => $attendance->check_in_time,
                'remarks' => $attendance->remarks,
                'excuse_reason' => $attendance->excuse_reason,
                'has_documentation' => $attendance->has_documentation
            ];
        }
        
        return [
            'user_id' => $userId,
            'course_id' => $courseId,
            'total_sessions' => $totalSessions,
            'attendance_rate' => round($attendanceRate, 2),
            'status_counts' => [
                'present' => $presentCount,
                'absent' => $absentCount,
                'late' => $lateCount,
                'excused' => $excusedCount
            ],
            'sessions' => $sessionDetails
        ];
    }
    
    /**
     * Identify at-risk students based on attendance.
     *
     * @param  int  $courseId
     * @param  float  $threshold
     * @return array
     */
    public function identifyAtRiskStudents($courseId, $threshold = 75)
    {
        // Get all enrolled students in the course
        $enrollments = Enrollment::where('course_id', $courseId)
                               ->where('status', 'active')
                               ->with('user')
                               ->get();
        
        $atRiskStudents = [];
        
        foreach ($enrollments as $enrollment) {
            $userId = $enrollment->user_id;
            $user = $enrollment->user;
            
            // Get attendance statistics for this student
            $stats = $this->getStudentAttendanceStatistics($userId, $courseId);
            
            // Check if the student is at risk based on attendance rate
            if ($stats['attendance_rate'] < $threshold) {
                $atRiskStudents[] = [
                    'user_id' => $userId,
                    'name' => $user->name,
                    'email' => $user->email,
                    'attendance_rate' => $stats['attendance_rate'],
                    'absent_count' => $stats['status_counts']['absent'],
                    'total_sessions' => $stats['total_sessions'],
                    'risk_level' => $this->calculateRiskLevel($stats['attendance_rate'])
                ];
            }
        }
        
        // Sort by attendance rate (ascending)
        usort($atRiskStudents, function($a, $b) {
            return $a['attendance_rate'] <=> $b['attendance_rate'];
        });
        
        return $atRiskStudents;
    }
    
    /**
     * Calculate risk level based on attendance rate.
     *
     * @param  float  $attendanceRate
     * @return string
     */
    private function calculateRiskLevel($attendanceRate)
    {
        if ($attendanceRate < 50) {
            return 'high';
        } elseif ($attendanceRate < 65) {
            return 'medium';
        } else {
            return 'low';
        }
    }
    
    /**
     * Generate attendance report for a course.
     *
     * @param  int  $courseId
     * @param  string  $startDate
     * @param  string  $endDate
     * @return array
     */
    public function generateAttendanceReport($courseId, $startDate = null, $endDate = null)
    {
        $course = Course::findOrFail($courseId);
        
        // Get all attendance records for the course within the date range
        $query = AttendanceRecord::where('course_id', $courseId);
        
        if ($startDate && $endDate) {
            $query->whereBetween('session_date', [$startDate, $endDate]);
        }
        
        $attendanceRecords = $query->orderBy('session_date')->get();
        
        // Get all enrolled students
        $enrollments = Enrollment::where('course_id', $courseId)
                               ->where('status', 'active')
                               ->with('user')
                               ->get();
        
        $students = $enrollments->pluck('user');
        
        // Initialize the report data
        $reportData = [
            'course' => [
                'id' => $course->id,
                'code' => $course->code,
                'title' => $course->title,
            ],
            'date_range' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'total_sessions' => $attendanceRecords->count(),
            'students' => [],
            'sessions' => [],
        ];
        
        // Prepare session data
        foreach ($attendanceRecords as $record) {
            $reportData['sessions'][] = [
                'id' => $record->id,
                'date' => $record->session_date,
                'start_time' => $record->start_time,
                'end_time' => $record->end_time,
                'topic' => $record->topic,
            ];
        }
        
        // Prepare student attendance data
        foreach ($students as $student) {
            $studentData = [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'attendance' => [],
            ];
            
            $studentStats = $this->getStudentAttendanceStatistics($student->id, $courseId);
            $studentData['attendance_rate'] = $studentStats['attendance_rate'];
            $studentData['status_counts'] = $studentStats['status_counts'];
            
            // Get attendance for each session
            foreach ($attendanceRecords as $record) {
                $attendance = StudentAttendance::where('attendance_record_id', $record->id)
                                            ->where('user_id', $student->id)
                                            ->first();
                
                $studentData['attendance'][] = [
                    'session_id' => $record->id,
                    'status' => $attendance ? $attendance->status : 'absent',
                    'check_in_time' => $attendance ? $attendance->check_in_time : null,
                    'remarks' => $attendance ? $attendance->remarks : null,
                ];
            }
            
            $reportData['students'][] = $studentData;
        }
        
        return $reportData;
    }
    
    /**
     * Send notifications to students with attendance issues.
     *
     * @param  int  $courseId
     * @param  float  $threshold
     * @return array
     */
    public function notifyStudentsWithAttendanceIssues($courseId, $threshold = 75)
    {
        // Identify at-risk students
        $atRiskStudents = $this->identifyAtRiskStudents($courseId, $threshold);
        
        $course = Course::findOrFail($courseId);
        
        $notifications = [];
        
        foreach ($atRiskStudents as $student) {
            // In a real system, this would send an email or other notification
            // For now, we'll just return the notification data
            
            $notification = [
                'user_id' => $student['user_id'],
                'email' => $student['email'],
                'subject' => 'Attendance Warning - ' . $course->code,
                'message' => "Dear {$student['name']},\n\n"
                    . "Your attendance rate in {$course->code} - {$course->title} is currently {$student['attendance_rate']}%, "
                    . "which is below the required threshold of {$threshold}%.\n\n"
                    . "You have been absent for {$student['absent_count']} out of {$student['total_sessions']} sessions.\n\n"
                    . "Please improve your attendance to avoid academic penalties.\n\n"
                    . "Regards,\nEl Sewedy University of Technology",
                'risk_level' => $student['risk_level'],
                'sent_at' => now()->toDateTimeString()
            ];
            
            $notifications[] = $notification;
        }
        
        return [
            'course_id' => $courseId,
            'threshold' => $threshold,
            'notifications_count' => count($notifications),
            'notifications' => $notifications
        ];
    }
}
