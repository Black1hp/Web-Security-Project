<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'amount',
        'type',
        'semester',
        'application_start_date',
        'application_end_date',
        'available_slots',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'application_start_date' => 'date',
        'application_end_date' => 'date',
        'available_slots' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the applications for this scholarship.
     */
    public function applications()
    {
        return $this->hasMany(ScholarshipApplication::class);
    }

    /**
     * Get the approved applications for this scholarship.
     */
    public function approvedApplications()
    {
        return $this->applications()->where('status', 'approved');
    }

    /**
     * Get the number of remaining slots.
     */
    public function remainingSlots()
    {
        $approvedCount = $this->approvedApplications()->count();
        return $this->available_slots - $approvedCount;
    }

    /**
     * Check if the scholarship has available slots.
     */
    public function hasAvailableSlots()
    {
        return $this->remainingSlots() > 0;
    }

    /**
     * Check if the scholarship is open for applications.
     */
    public function isOpenForApplications()
    {
        $now = now();
        return $this->is_active && 
               $now >= $this->application_start_date && 
               $now <= $this->application_end_date && 
               $this->hasAvailableSlots();
    }

    /**
     * Scope a query to only include active scholarships.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include scholarships open for applications.
     */
    public function scopeOpenForApplications($query)
    {
        $now = now();
        return $query->where('is_active', true)
                    ->where('application_start_date', '<=', $now)
                    ->where('application_end_date', '>=', $now)
                    ->whereRaw('available_slots > (SELECT COUNT(*) FROM scholarship_applications WHERE scholarship_id = scholarships.id AND status = "approved")');
    }
}
