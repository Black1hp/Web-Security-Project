<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'transaction_type',
        'reference_type',
        'reference_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'description',
        'due_date',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'reference_id' => 'integer',
        'amount' => 'decimal:2',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that the financial record belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the referenced entity.
     */
    public function reference()
    {
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id');
    }

    /**
     * Get the document attachments for this financial record.
     */
    public function attachments()
    {
        return $this->morphMany(DocumentAttachment::class, 'attachable');
    }

    /**
     * Scope a query to only include tuition records.
     */
    public function scopeTuition($query)
    {
        return $query->where('transaction_type', 'tuition');
    }

    /**
     * Scope a query to only include fee records.
     */
    public function scopeFee($query)
    {
        return $query->where('transaction_type', 'fee');
    }

    /**
     * Scope a query to only include payment records.
     */
    public function scopePayment($query)
    {
        return $query->where('transaction_type', 'payment');
    }

    /**
     * Scope a query to only include refund records.
     */
    public function scopeRefund($query)
    {
        return $query->where('transaction_type', 'refund');
    }

    /**
     * Scope a query to only include scholarship records.
     */
    public function scopeScholarship($query)
    {
        return $query->where('transaction_type', 'scholarship');
    }

    /**
     * Scope a query to only include pending records.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed records.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include failed records.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to only include refunded records.
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    /**
     * Scope a query to only include overdue records.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                    ->where('due_date', '<', now());
    }
}
