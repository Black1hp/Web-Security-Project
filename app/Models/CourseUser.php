<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseUser extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'user_id',
        'role_type',
        'responsibilities',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'course_id' => 'integer',
        'user_id' => 'integer',
        'role_type' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'course_user';

    /**
     * Get the course that the user is associated with.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user that is associated with the course.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include instructors.
     */
    public function scopeInstructors($query)
    {
        return $query->where('role_type', 'instructor');
    }

    /**
     * Scope a query to only include teaching assistants.
     */
    public function scopeTeachingAssistants($query)
    {
        return $query->where('role_type', 'teaching_assistant');
    }

    /**
     * Scope a query to only include students.
     */
    public function scopeStudents($query)
    {
        return $query->where('role_type', 'student');
    }
} 