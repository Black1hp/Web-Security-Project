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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'credits' => 'integer',
        'professor_id' => 'integer',
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
} 