<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'scholarship_id',
        'statement_of_purpose',
        'current_gpa',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'scholarship_id' => 'integer',
        'current_gpa' => 'decimal:2',
        'reviewed_by' => 'integer',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that submitted the application.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the scholarship that the application is for.
     */
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    /**
     * Get the user that reviewed the application.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the document attachments for this application.
     */
    public function attachments()
    {
        return $this->morphMany(DocumentAttachment::class, 'attachable');
    }

    /**
     * Scope a query to only include pending applications.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved applications.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected applications.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Approve the application.
     */
    public function approve($reviewerId)
    {
        $this->status = 'approved';
        $this->reviewed_by = $reviewerId;
        $this->reviewed_at = now();
        
        if ($this->save()) {
            // Create a financial record for the scholarship
            FinancialRecord::create([
                'user_id' => $this->user_id,
                'transaction_type' => 'scholarship',
                'reference_type' => 'App\Models\ScholarshipApplication',
                'reference_id' => $this->id,
                'amount' => $this->scholarship->amount,
                'status' => 'completed',
                'description' => 'Scholarship: ' . $this->scholarship->name,
            ]);
            
            return true;
        }
        
        return false;
    }

    /**
     * Reject the application.
     */
    public function reject($reviewerId, $reason)
    {
        $this->status = 'rejected';
        $this->rejection_reason = $reason;
        $this->reviewed_by = $reviewerId;
        $this->reviewed_at = now();
        
        return $this->save();
    }
}
