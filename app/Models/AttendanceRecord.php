<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'session_date',
        'start_time',
        'end_time',
        'topic',
        'recorded_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'course_id' => 'integer',
        'session_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'recorded_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the course that the attendance record belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user that recorded the attendance.
     */
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Get the student attendances for this record.
     */
    public function studentAttendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    /**
     * Get the attendance rate for this session.
     */
    public function attendanceRate()
    {
        $total = $this->studentAttendances()->count();
        if ($total === 0) {
            return 0;
        }
        
        $present = $this->studentAttendances()
            ->whereIn('status', ['present', 'late'])
            ->count();
        
        return ($present / $total) * 100;
    }

    /**
     * Get the absent students for this session.
     */
    public function absentStudents()
    {
        return $this->studentAttendances()
            ->where('status', 'absent')
            ->with('user')
            ->get()
            ->pluck('user');
    }

    /**
     * Get the present students for this session.
     */
    public function presentStudents()
    {
        return $this->studentAttendances()
            ->where('status', 'present')
            ->with('user')
            ->get()
            ->pluck('user');
    }

    /**
     * Get the late students for this session.
     */
    public function lateStudents()
    {
        return $this->studentAttendances()
            ->where('status', 'late')
            ->with('user')
            ->get()
            ->pluck('user');
    }

    /**
     * Get the excused students for this session.
     */
    public function excusedStudents()
    {
        return $this->studentAttendances()
            ->where('status', 'excused')
            ->with('user')
            ->get()
            ->pluck('user');
    }
}
