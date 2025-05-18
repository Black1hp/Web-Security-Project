<?php

namespace App\Services;

use App\Models\FinancialRecord;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\PaymentPlan;
use App\Models\PaymentPlanInstallment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialService
{
    /**
     * Process a payment for a financial record.
     *
     * @param  \App\Models\FinancialRecord  $record
     * @param  string  $paymentMethod
     * @param  float  $amount
     * @param  string|null  $transactionId
     * @return array
     */
    public function processPayment(FinancialRecord $record, $paymentMethod, $amount, $transactionId = null)
    {
        // Check if the record is already paid
        if ($record->status === 'completed') {
            return [
                'success' => false,
                'message' => 'This record has already been paid.'
            ];
        }
        
        // Check if the record is eligible for payment
        if (!in_array($record->status, ['pending', 'failed'])) {
            return [
                'success' => false,
                'message' => 'This record is not eligible for payment.'
            ];
        }
        
        // Check if the amount is sufficient
        if ($amount < $record->amount) {
            return [
                'success' => false,
                'message' => 'The payment amount is less than the required amount.'
            ];
        }
        
        try {
            DB::beginTransaction();
            
            // Update the record
            $record->status = 'completed';
            $record->payment_method = $paymentMethod;
            $record->transaction_id = $transactionId;
            $record->paid_at = now();
            $record->save();
            
            // If the payment is more than the required amount, create a credit record
            if ($amount > $record->amount) {
                $creditAmount = $amount - $record->amount;
                
                $creditRecord = FinancialRecord::create([
                    'user_id' => $record->user_id,
                    'transaction_type' => 'credit',
                    'reference_type' => 'App\Models\FinancialRecord',
                    'reference_id' => $record->id,
                    'amount' => $creditAmount,
                    'status' => 'completed',
                    'description' => 'Credit from overpayment',
                    'payment_method' => $paymentMethod,
                    'transaction_id' => $transactionId,
                    'paid_at' => now(),
                ]);
            }
            
            // Create a receipt record
            $receiptRecord = [
                'record_id' => $record->id,
                'payment_method' => $paymentMethod,
                'amount' => $amount,
                'transaction_id' => $transactionId,
                'payment_date' => now()->toDateTimeString(),
                'receipt_number' => 'RCPT-' . now()->format('YmdHis') . '-' . $record->id,
            ];
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Payment processed successfully.',
                'data' => [
                    'record' => $record,
                    'credit' => $creditAmount ?? 0,
                    'receipt' => $receiptRecord
                ]
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Failed to process payment: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate a financial statement for a user.
     *
     * @param  \App\Models\User  $user
     * @param  string  $startDate
     * @param  string  $endDate
     * @param  bool  $includePending
     * @return array
     */
    public function generateFinancialStatement(User $user, $startDate, $endDate, $includePending = true)
    {
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        $query = FinancialRecord::where('user_id', $user->id)
                               ->whereBetween('created_at', [$startDate, $endDate]);
        
        if (!$includePending) {
            $query->where('status', '!=', 'pending');
        }
        
        $records = $query->orderBy('created_at')->get();
        
        // Calculate totals
        $totalTuition = $records->where('transaction_type', 'tuition')->sum('amount');
        $totalFees = $records->where('transaction_type', 'fee')->sum('amount');
        $totalPayments = $records->where('transaction_type', 'payment')->where('status', 'completed')->sum('amount');
        $totalRefunds = $records->where('transaction_type', 'refund')->where('status', 'completed')->sum('amount');
        $totalScholarships = $records->where('transaction_type', 'scholarship')->where('status', 'completed')->sum('amount');
        
        // Calculate balance
        $totalCharges = $totalTuition + $totalFees;
        $totalCredits = $totalPayments + $totalRefunds + $totalScholarships;
        $balance = $totalCharges - $totalCredits;
        
        // Get pending payments
        $pendingPayments = $records->where('status', 'pending')
                                 ->where(function($query) {
                                     $query->where('transaction_type', 'tuition')
                                           ->orWhere('transaction_type', 'fee');
                                 })
                                 ->sum('amount');
        
        // Format the records for the statement
        $formattedRecords = $records->map(function($record) {
            return [
                'id' => $record->id,
                'date' => $record->created_at->format('Y-m-d'),
                'description' => $record->description,
                'type' => $record->transaction_type,
                'amount' => $record->amount,
                'status' => $record->status,
                'due_date' => $record->due_date ? Carbon::parse($record->due_date)->format('Y-m-d') : null,
                'paid_at' => $record->paid_at ? Carbon::parse($record->paid_at)->format('Y-m-d') : null,
            ];
        });
        
        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'statement_period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ],
            'summary' => [
                'total_tuition' => $totalTuition,
                'total_fees' => $totalFees,
                'total_payments' => $totalPayments,
                'total_refunds' => $totalRefunds,
                'total_scholarships' => $totalScholarships,
                'total_charges' => $totalCharges,
                'total_credits' => $totalCredits,
                'balance' => $balance,
                'pending_payments' => $pendingPayments,
            ],
            'records' => $formattedRecords,
            'generated_at' => now()->toDateTimeString(),
        ];
    }
    
    /**
     * Check if a user has any financial holds.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function checkFinancialHolds(User $user)
    {
        $holds = [];
        
        // Check for overdue payments
        $overdueRecords = FinancialRecord::where('user_id', $user->id)
                                       ->where('status', 'pending')
                                       ->where('due_date', '<', now())
                                       ->get();
        
        foreach ($overdueRecords as $record) {
            $holds[] = [
                'id' => $record->id,
                'type' => 'overdue_payment',
                'description' => 'Overdue payment: ' . $record->description,
                'amount' => $record->amount,
                'due_date' => $record->due_date,
                'days_overdue' => now()->diffInDays(Carbon::parse($record->due_date)),
            ];
        }
        
        // Check for payment plan defaults
        $defaultedPlans = PaymentPlan::where('user_id', $user->id)
                                   ->where('status', 'defaulted')
                                   ->get();
        
        foreach ($defaultedPlans as $plan) {
            $holds[] = [
                'id' => $plan->id,
                'type' => 'defaulted_payment_plan',
                'description' => 'Defaulted payment plan for ' . $plan->semester,
                'amount' => $plan->remainingAmount(),
                'start_date' => $plan->start_date,
            ];
        }
        
        // Check for overdue installments
        $overdueInstallments = PaymentPlanInstallment::whereHas('paymentPlan', function($query) use ($user) {
                                                    $query->where('user_id', $user->id);
                                                })
                                                ->where('status', 'overdue')
                                                ->get();
        
        foreach ($overdueInstallments as $installment) {
            $holds[] = [
                'id' => $installment->id,
                'type' => 'overdue_installment',
                'description' => 'Overdue installment #' . $installment->installment_number . ' for ' . $installment->paymentPlan->semester,
                'amount' => $installment->amount,
                'due_date' => $installment->due_date,
                'days_overdue' => now()->diffInDays(Carbon::parse($installment->due_date)),
            ];
        }
        
        return $holds;
    }
    
    /**
     * Create a payment plan for a user.
     *
     * @param  \App\Models\User  $user
     * @param  string  $semester
     * @param  float  $totalAmount
     * @param  int  $numberOfInstallments
     * @param  string  $startDate
     * @return array
     */
    public function createPaymentPlan(User $user, $semester, $totalAmount, $numberOfInstallments, $startDate)
    {
        // Check if the user already has a payment plan for this semester
        $existingPlan = PaymentPlan::where('user_id', $user->id)
                                 ->where('semester', $semester)
                                 ->first();
        
        if ($existingPlan) {
            return [
                'success' => false,
                'message' => 'You already have a payment plan for this semester.'
            ];
        }
        
        // Calculate installment amount
        $installmentAmount = round($totalAmount / $numberOfInstallments, 2);
        
        try {
            DB::beginTransaction();
            
            // Create the payment plan
            $plan = PaymentPlan::create([
                'user_id' => $user->id,
                'semester' => $semester,
                'total_amount' => $totalAmount,
                'number_of_installments' => $numberOfInstallments,
                'installment_amount' => $installmentAmount,
                'start_date' => $startDate,
                'status' => 'active',
            ]);
            
            // Create the installments
            $startDate = Carbon::parse($startDate);
            $installments = [];
            
            for ($i = 1; $i <= $numberOfInstallments; $i++) {
                $dueDate = ($i === 1) ? $startDate : $startDate->copy()->addMonths($i - 1);
                
                $installment = PaymentPlanInstallment::create([
                    'payment_plan_id' => $plan->id,
                    'installment_number' => $i,
                    'amount' => $installmentAmount,
                    'due_date' => $dueDate,
                    'status' => 'pending',
                ]);
                
                $installments[] = $installment;
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Payment plan created successfully.',
                'data' => [
                    'plan' => $plan,
                    'installments' => $installments
                ]
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Failed to create payment plan: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Process a refund for a dropped course.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return array
     */
    public function processRefundForDroppedCourse(Enrollment $enrollment)
    {
        // Check if the enrollment is actually dropped
        if ($enrollment->status !== 'dropped') {
            return [
                'success' => false,
                'message' => 'The enrollment is not dropped.'
            ];
        }
        
        // Find the tuition record for this enrollment
        $tuitionRecord = FinancialRecord::where('user_id', $enrollment->user_id)
                                      ->where('transaction_type', 'tuition')
                                      ->where('reference_type', 'App\Models\Enrollment')
                                      ->where('reference_id', $enrollment->id)
                                      ->first();
        
        if (!$tuitionRecord) {
            return [
                'success' => false,
                'message' => 'No tuition record found for this enrollment.'
            ];
        }
        
        // Check if a refund already exists
        $existingRefund = FinancialRecord::where('user_id', $enrollment->user_id)
                                       ->where('transaction_type', 'refund')
                                       ->where('reference_type', 'App\Models\Enrollment')
                                       ->where('reference_id', $enrollment->id)
                                       ->exists();
        
        if ($existingRefund) {
            return [
                'success' => false,
                'message' => 'A refund already exists for this enrollment.'
            ];
        }
        
        // Calculate the refund amount based on when the course was dropped
        $course = $enrollment->course;
        $dropDate = $enrollment->updated_at;
        $registrationEnd = Carbon::parse($course->registration_end);
        $refundPercentage = 0;
        
        // Within 1 week of registration end: 100% refund
        if ($dropDate->lte($registrationEnd->copy()->addDays(7))) {
            $refundPercentage = 1.0;
        } 
        // Within 2 weeks of registration end: 75% refund
        elseif ($dropDate->lte($registrationEnd->copy()->addDays(14))) {
            $refundPercentage = 0.75;
        } 
        // Within 3 weeks of registration end: 50% refund
        elseif ($dropDate->lte($registrationEnd->copy()->addDays(21))) {
            $refundPercentage = 0.5;
        } 
        // Within 4 weeks of registration end: 25% refund
        elseif ($dropDate->lte($registrationEnd->copy()->addDays(28))) {
            $refundPercentage = 0.25;
        }
        
        // No refund after 4 weeks
        if ($refundPercentage === 0) {
            return [
                'success' => false,
                'message' => 'No refund is available for this course drop.'
            ];
        }
        
        try {
            // Calculate the refund amount
            $refundAmount = $tuitionRecord->amount * $refundPercentage;
            
            // Create the refund record
            $refundRecord = FinancialRecord::create([
                'user_id' => $enrollment->user_id,
                'transaction_type' => 'refund',
                'reference_type' => 'App\Models\Enrollment',
                'reference_id' => $enrollment->id,
                'amount' => $refundAmount,
                'status' => 'pending',
                'description' => 'Refund for dropped course: ' . $course->code . ' - ' . $course->title . ' (' . ($refundPercentage * 100) . '%)',
            ]);
            
            return [
                'success' => true,
                'message' => 'Refund processed successfully.',
                'data' => [
                    'refund_record' => $refundRecord,
                    'refund_percentage' => $refundPercentage,
                    'refund_amount' => $refundAmount
                ]
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to process refund: ' . $e->getMessage()
            ];
        }
    }
}
