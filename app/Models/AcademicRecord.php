<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'semester',
        'semester_gpa',
        'cumulative_gpa',
        'credits_attempted',
        'credits_earned',
        'academic_standing',
        'deans_list',
        'honors',
        'notes',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'semester_gpa' => 'decimal:2',
        'cumulative_gpa' => 'decimal:2',
        'credits_attempted' => 'integer',
        'credits_earned' => 'integer',
        'deans_list' => 'boolean',
        'honors' => 'boolean',
        'updated_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that the academic record belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that updated the record.
     */
    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include records with good standing.
     */
    public function scopeGoodStanding($query)
    {
        return $query->where('academic_standing', 'good standing');
    }

    /**
     * Scope a query to only include records with probation.
     */
    public function scopeProbation($query)
    {
        return $query->where('academic_standing', 'probation');
    }

    /**
     * Scope a query to only include records with suspension.
     */
    public function scopeSuspension($query)
    {
        return $query->where('academic_standing', 'suspension');
    }

    /**
     * Scope a query to only include records with dismissal.
     */
    public function scopeDismissed($query)
    {
        return $query->where('academic_standing', 'dismissed');
    }

    /**
     * Scope a query to only include records with dean's list.
     */
    public function scopeDeansList($query)
    {
        return $query->where('deans_list', true);
    }

    /**
     * Scope a query to only include records with honors.
     */
    public function scopeHonors($query)
    {
        return $query->where('honors', true);
    }

    /**
     * Calculate academic standing based on GPA.
     */
    public static function calculateAcademicStanding($gpa)
    {
        if ($gpa >= 2.0) {
            return 'good standing';
        } elseif ($gpa >= 1.7) {
            return 'probation';
        } elseif ($gpa >= 1.0) {
            return 'suspension';
        } else {
            return 'dismissed';
        }
    }

    /**
     * Check if the student qualifies for dean's list.
     */
    public static function qualifiesForDeansList($gpa, $creditsEarned)
    {
        return $gpa >= 3.5 && $creditsEarned >= 12;
    }

    /**
     * Check if the student qualifies for honors.
     */
    public static function qualifiesForHonors($gpa)
    {
        return $gpa >= 3.8;
    }
}
