<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'attendance_record_id',
        'user_id',
        'status',
        'check_in_time',
        'remarks',
        'excuse_reason',
        'has_documentation',
        'documentation_path',
        'verified_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attendance_record_id' => 'integer',
        'user_id' => 'integer',
        'check_in_time' => 'datetime',
        'has_documentation' => 'boolean',
        'verified_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the attendance record that the student attendance belongs to.
     */
    public function attendanceRecord()
    {
        return $this->belongsTo(AttendanceRecord::class);
    }

    /**
     * Get the user (student) that the attendance record is for.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that verified the excuse.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the document attachments for this attendance record.
     */
    public function attachments()
    {
        return $this->morphMany(DocumentAttachment::class, 'attachable');
    }

    /**
     * Scope a query to only include present students.
     */
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    /**
     * Scope a query to only include absent students.
     */
    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    /**
     * Scope a query to only include late students.
     */
    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    /**
     * Scope a query to only include excused students.
     */
    public function scopeExcused($query)
    {
        return $query->where('status', 'excused');
    }

    /**
     * Mark the student as present.
     */
    public function markAsPresent($checkInTime = null)
    {
        $this->status = 'present';
        
        if ($checkInTime) {
            $this->check_in_time = $checkInTime;
        } else {
            $this->check_in_time = now();
        }
        
        return $this->save();
    }

    /**
     * Mark the student as absent.
     */
    public function markAsAbsent($remarks = null)
    {
        $this->status = 'absent';
        
        if ($remarks) {
            $this->remarks = $remarks;
        }
        
        return $this->save();
    }

    /**
     * Mark the student as late.
     */
    public function markAsLate($checkInTime = null, $remarks = null)
    {
        $this->status = 'late';
        
        if ($checkInTime) {
            $this->check_in_time = $checkInTime;
        } else {
            $this->check_in_time = now();
        }
        
        if ($remarks) {
            $this->remarks = $remarks;
        }
        
        return $this->save();
    }

    /**
     * Mark the student as excused.
     */
    public function markAsExcused($reason, $hasDocumentation = false, $documentationPath = null, $verifierId = null)
    {
        $this->status = 'excused';
        $this->excuse_reason = $reason;
        $this->has_documentation = $hasDocumentation;
        
        if ($documentationPath) {
            $this->documentation_path = $documentationPath;
        }
        
        if ($verifierId) {
            $this->verified_by = $verifierId;
        }
        
        return $this->save();
    }
}
