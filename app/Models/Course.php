<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'title',
        'description',
        'credits',
        'professor_id',
        'department_id',
        'semester',
        'capacity',
        'enrolled_count',
        'is_active',
        'registration_start',
        'registration_end',
        'tuition_per_credit',
        'syllabus',
        'location',
        'meeting_days',
        'start_time',
        'end_time'

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'credits' => 'integer',
        'professor_id' => 'integer',
        'department_id' => 'integer',
        'capacity' => 'integer',
        'enrolled_count' => 'integer',
        'is_active' => 'boolean',
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'tuition_per_credit' => 'decimal:2',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the professor for the course.
     */
    public function professor()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->wherePivot('role_type', 'professor')
            ->withTimestamps();
    }

    /**
     * Get the students enrolled in the course.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->wherePivot('role_type', 'student')
            ->withTimestamps();
    }

    /**
     * Get the grades for the course.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the teaching assistants for the course.
     */
    public function teachingAssistants()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->wherePivot('role_type', 'teaching_assistant')
            ->withTimestamps();
    }

    /**
     * Get the department that the course belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the prerequisites for this course.
     */
    public function prerequisites()
    {
        return $this->belongsToMany(
            Course::class, 
            'course_prerequisites', 
            'course_id', 
            'prerequisite_id'
        )->withPivot('min_grade')->withTimestamps();
    }

    /**
     * Get the courses that have this course as a prerequisite.
     */
    public function followingCourses()
    {
        return $this->belongsToMany(
            Course::class, 
            'course_prerequisites', 
            'prerequisite_id', 
            'course_id'
        )->withPivot('min_grade')->withTimestamps();
    }

    /**
     * Get the waitlist for this course.
     */
    public function waitlist()
    {
        return $this->hasMany(CourseWaitlist::class);
    }

    /**
     * Get the enrollments for this course.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the attendance records for this course.
     */
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    /**
     * Check if the course is full.
     */
    public function isFull()
    {
        return $this->enrolled_count >= $this->capacity;
    }

    /**
     * Check if registration is open.
     */
    public function isRegistrationOpen()
    {
        $now = now();
        return $this->is_active && 
               $now >= $this->registration_start && 
               $now <= $this->registration_end;
    }

    /**
     * Calculate the total tuition for this course.
     */
    public function calculateTuition()
    {
        return $this->credits * $this->tuition_per_credit;
    }
} 