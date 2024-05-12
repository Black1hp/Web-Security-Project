<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FinancialRecord;
use App\Models\User;
use App\Services\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FinancialRecordController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the financial records for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = FinancialRecord::where('user_id', $user->id);
        
        // Filter by transaction type
        if ($request->has('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        
        $records = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $records
        ]);
    }

    /**
     * Store a newly created financial record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', FinancialRecord::class);
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'transaction_type' => 'required|string|in:tuition,fee,payment,refund,scholarship',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|integer',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'status' => 'required|string|in:pending,completed,failed,refunded',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $record = FinancialRecord::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Financial record created successfully',
                'data' => $record
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create financial record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified financial record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $record = FinancialRecord::findOrFail($id);
        
        $this->authorize('view', $record);
        
        return response()->json([
            'success' => true,
            'data' => $record
        ]);
    }

    /**
     * Update the specified financial record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $record = FinancialRecord::findOrFail($id);
        
        $this->authorize('update', $record);
        
        $validator = Validator::make($request->all(), [
            'transaction_type' => 'sometimes|required|string|in:tuition,fee,payment,refund,scholarship',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|integer',
            'amount' => 'sometimes|required|numeric|min:0',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'status' => 'sometimes|required|string|in:pending,completed,failed,refunded',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'paid_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $record->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Financial record updated successfully',
                'data' => $record
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update financial record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified financial record from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = FinancialRecord::findOrFail($id);
        
        $this->authorize('delete', $record);
        
        try {
            $record->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Financial record deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete financial record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process a payment for a financial record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processPayment(Request $request, $id)
    {
        $record = FinancialRecord::findOrFail($id);
        
        $this->authorize('update', $record);
        
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->financialService->processPayment(
                $record,
                $request->payment_method,
                $request->amount,
                $request->transaction_id
            );
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => $result['data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 422);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a financial statement for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateStatement(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'include_pending' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $startDate = $request->input('start_date', now()->subYear());
            $endDate = $request->input('end_date', now());
            $includePending = $request->input('include_pending', true);
            
            $statement = $this->financialService->generateFinancialStatement(
                $user,
                $startDate,
                $endDate,
                $includePending
            );
            
            return response()->json([
                'success' => true,
                'data' => $statement
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate financial statement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if the user has any financial holds.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkFinancialHolds()
    {
        $user = Auth::user();
        
        try {
            $holds = $this->financialService->checkFinancialHolds($user);
            
            return response()->json([
                'success' => true,
                'has_holds' => count($holds) > 0,
                'holds' => $holds
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check financial holds',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
