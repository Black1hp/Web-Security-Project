<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'enrollment_id',
        'grade',
        'points',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enrollment_id' => 'integer',
        'grade' => 'string',
        'points' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the enrollment that owns the grade.
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Get the user through the enrollment.
     */
    public function user()
    {
        return $this->hasOneThrough(User::class, Enrollment::class);
    }

    /**
     * Get the course through the enrollment.
     */
    public function course()
    {
        return $this->hasOneThrough(Course::class, Enrollment::class);
    }

    /**
     * Scope a query to only include passing grades.
     */
    public function scopePassing($query)
    {
        return $query->where('points', '>=', 2.0);
    }

    /**
     * Scope a query to only include failing grades.
     */
    public function scopeFailing($query)
    {
        return $query->where('points', '<', 2.0);
    }

    /**
     * Scope a query to only include excellent grades (A).
     */
    public function scopeExcellent($query)
    {
        return $query->where('grade', 'A');
    }

    /**
     * Scope a query to only include good grades (B).
     */
    public function scopeGood($query)
    {
        return $query->where('grade', 'B');
    }

    /**
     * Scope a query to only include satisfactory grades (C).
     */
    public function scopeSatisfactory($query)
    {
        return $query->where('grade', 'C');
    }
} 