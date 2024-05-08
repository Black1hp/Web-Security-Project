<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'request_type',
        'reference_type',
        'reference_id',
        'reason',
        'description',
        'status',
        'approval_workflow',
        'approval_history',
        'current_approver_id',
        'rejection_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'reference_id' => 'integer',
        'approval_workflow' => 'json',
        'approval_history' => 'json',
        'current_approver_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that submitted the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the current approver for the request.
     */
    public function currentApprover()
    {
        return $this->belongsTo(User::class, 'current_approver_id');
    }

    /**
     * Get the referenced entity.
     */
    public function reference()
    {
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id');
    }

    /**
     * Get the document attachments for this request.
     */
    public function attachments()
    {
        return $this->morphMany(DocumentAttachment::class, 'attachable');
    }

    /**
     * Scope a query to only include pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected requests.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include in-review requests.
     */
    public function scopeInReview($query)
    {
        return $query->where('status', 'in_review');
    }

    /**
     * Approve the request.
     */
    public function approve($approverId, $comments = null)
    {
        // Get the current approval history or initialize an empty array
        $approvalHistory = $this->approval_history ?? [];
        
        // Add the current approval to the history
        $approvalHistory[] = [
            'approver_id' => $approverId,
            'action' => 'approved',
            'comments' => $comments,
            'timestamp' => now()->toDateTimeString(),
        ];
        
        // Update the approval history
        $this->approval_history = $approvalHistory;
        
        // Check if this is the final approval in the workflow
        $workflow = $this->approval_workflow ?? [];
        $currentApproverIndex = array_search($this->current_approver_id, array_column($workflow, 'approver_id'));
        
        if ($currentApproverIndex !== false && isset($workflow[$currentApproverIndex + 1])) {
            // Move to the next approver in the workflow
            $this->status = 'in_review';
            $this->current_approver_id = $workflow[$currentApproverIndex + 1]['approver_id'];
        } else {
            // This is the final approval
            $this->status = 'approved';
            $this->current_approver_id = null;
        }
        
        return $this->save();
    }

    /**
     * Reject the request.
     */
    public function reject($approverId, $reason, $comments = null)
    {
        // Get the current approval history or initialize an empty array
        $approvalHistory = $this->approval_history ?? [];
        
        // Add the rejection to the history
        $approvalHistory[] = [
            'approver_id' => $approverId,
            'action' => 'rejected',
            'comments' => $comments,
            'timestamp' => now()->toDateTimeString(),
        ];
        
        // Update the approval history and status
        $this->approval_history = $approvalHistory;
        $this->status = 'rejected';
        $this->rejection_reason = $reason;
        $this->current_approver_id = null;
        
        return $this->save();
    }

    /**
     * Initialize the approval workflow for the request.
     */
    public function initializeWorkflow($workflow)
    {
        if (empty($workflow)) {
            return false;
        }
        
        $this->approval_workflow = $workflow;
        $this->current_approver_id = $workflow[0]['approver_id'];
        $this->status = 'in_review';
        $this->approval_history = [];
        
        return $this->save();
    }
}
