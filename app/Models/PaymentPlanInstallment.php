<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlanInstallment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_plan_id',
        'installment_number',
        'amount',
        'due_date',
        'paid_date',
        'status',
        'payment_method',
        'transaction_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payment_plan_id' => 'integer',
        'installment_number' => 'integer',
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the payment plan that the installment belongs to.
     */
    public function paymentPlan()
    {
        return $this->belongsTo(PaymentPlan::class);
    }

    /**
     * Scope a query to only include pending installments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include paid installments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include overdue installments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Check if the installment is overdue.
     */
    public function isOverdue()
    {
        return $this->status === 'pending' && $this->due_date < now();
    }

    /**
     * Mark the installment as paid.
     */
    public function markAsPaid($paymentMethod = null, $transactionId = null)
    {
        $this->status = 'paid';
        $this->paid_date = now();
        
        if ($paymentMethod) {
            $this->payment_method = $paymentMethod;
        }
        
        if ($transactionId) {
            $this->transaction_id = $transactionId;
        }
        
        return $this->save();
    }

    /**
     * Mark the installment as overdue.
     */
    public function markAsOverdue()
    {
        if ($this->status === 'pending' && $this->due_date < now()) {
            $this->status = 'overdue';
            return $this->save();
        }
        
        return false;
    }
}
