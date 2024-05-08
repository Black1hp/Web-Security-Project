<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
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
        'total_amount',
        'number_of_installments',
        'installment_amount',
        'start_date',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'total_amount' => 'decimal:2',
        'number_of_installments' => 'integer',
        'installment_amount' => 'decimal:2',
        'start_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that the payment plan belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the installments for this payment plan.
     */
    public function installments()
    {
        return $this->hasMany(PaymentPlanInstallment::class);
    }

    /**
     * Scope a query to only include active payment plans.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include completed payment plans.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include defaulted payment plans.
     */
    public function scopeDefaulted($query)
    {
        return $query->where('status', 'defaulted');
    }

    /**
     * Check if the payment plan has any overdue installments.
     */
    public function hasOverdueInstallments()
    {
        return $this->installments()
            ->where('status', 'overdue')
            ->exists();
    }

    /**
     * Get the number of paid installments.
     */
    public function paidInstallmentsCount()
    {
        return $this->installments()
            ->where('status', 'paid')
            ->count();
    }

    /**
     * Get the remaining amount to be paid.
     */
    public function remainingAmount()
    {
        $paidAmount = $this->installments()
            ->where('status', 'paid')
            ->sum('amount');
        
        return $this->total_amount - $paidAmount;
    }
}
