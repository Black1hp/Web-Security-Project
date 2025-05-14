<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StudentRequest;
use App\Models\DocumentAttachment;
use App\Models\User;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StudentRequestController extends Controller
{
    protected $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the student requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Determine the role of the user
        $isAdmin = $user->hasRole('admin');
        $isAdvisor = $user->hasRole('advisor');
        $isDepartmentChair = $user->hasRole('department_chair');
        $isRegistrar = $user->hasRole('registrar');
        
        $query = StudentRequest::query();
        
        // Filter based on user role
        if (!$isAdmin) {
            if ($isAdvisor || $isDepartmentChair || $isRegistrar) {
                // Show requests where the user is the current approver
                $query->where('current_approver_id', $user->id);
            } else {
                // Regular student - show only their own requests
                $query->where('user_id', $user->id);
            }
        }
        
        // Filter by request type
        if ($request->has('request_type')) {
            $query->where('request_type', $request->request_type);
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        
        $requests = $query->with(['user'])->orderBy('created_at', 'desc')->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    /**
     * Store a newly created student request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_type' => 'required|string',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|integer',
            'reason' => 'required|string',
            'description' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // Max 10MB per file
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        
        try {
            DB::beginTransaction();
            
            // Create the student request
            $studentRequest = StudentRequest::create([
                'user_id' => $user->id,
                'request_type' => $request->request_type,
                'reference_type' => $request->reference_type,
                'reference_id' => $request->reference_id,
                'reason' => $request->reason,
                'description' => $request->description,
                'status' => 'pending',
            ]);
            
            // Initialize the approval workflow
            $workflow = $this->workflowService->initializeWorkflow($studentRequest);
            
            // Upload attachments if provided
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('request_attachments');
                    
                    $attachment = DocumentAttachment::create([
                        'attachable_type' => 'App\Models\StudentRequest',
                        'attachable_id' => $studentRequest->id,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => $user->id,
                    ]);
                    
                    $attachments[] = $attachment;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Request submitted successfully',
                'data' => [
                    'request' => $studentRequest,
                    'workflow' => $workflow,
                    'attachments' => $attachments
                ]
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified student request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studentRequest = StudentRequest::with(['user', 'attachments'])->findOrFail($id);
        
        // Check if the user is authorized to view this request
        $this->authorize('view', $studentRequest);
        
        return response()->json([
            'success' => true,
            'data' => $studentRequest
        ]);
    }

    /**
     * Update the specified student request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $studentRequest = StudentRequest::findOrFail($id);
        
        // Check if the user is authorized to update this request
        $this->authorize('update', $studentRequest);
        
        $validator = Validator::make($request->all(), [
            'reason' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // Max 10MB per file
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Only allow updates if the request is still pending
        if ($studentRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update a request that is already in review, approved, or rejected.'
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Update the student request
            $studentRequest->update([
                'reason' => $request->reason ?? $studentRequest->reason,
                'description' => $request->description ?? $studentRequest->description,
            ]);
            
            // Upload additional attachments if provided
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('request_attachments');
                    
                    $attachment = DocumentAttachment::create([
                        'attachable_type' => 'App\Models\StudentRequest',
                        'attachable_id' => $studentRequest->id,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => Auth::id(),
                    ]);
                    
                    $attachments[] = $attachment;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Request updated successfully',
                'data' => [
                    'request' => $studentRequest,
                    'new_attachments' => $attachments
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified student request from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $studentRequest = StudentRequest::findOrFail($id);
        
        // Check if the user is authorized to delete this request
        $this->authorize('delete', $studentRequest);
        
        // Only allow deletion if the request is still pending
        if ($studentRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a request that is already in review, approved, or rejected.'
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Delete attachments
            $attachments = DocumentAttachment::where('attachable_type', 'App\Models\StudentRequest')
                                          ->where('attachable_id', $studentRequest->id)
                                          ->get();
            
            foreach ($attachments as $attachment) {
                Storage::delete($attachment->file_path);
                $attachment->delete();
            }
            
            // Delete the request
            $studentRequest->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Request deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a student request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'comments' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $studentRequest = StudentRequest::findOrFail($id);
        
        // Check if the user is authorized to approve this request
        $this->authorize('approve', $studentRequest);
        
        try {
            $result = $this->workflowService->approveRequest(
                $studentRequest,
                Auth::id(),
                $request->comments
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
                'message' => 'Failed to approve request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a student request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string',
            'comments' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $studentRequest = StudentRequest::findOrFail($id);
        
        // Check if the user is authorized to reject this request
        $this->authorize('reject', $studentRequest);
        
        try {
            $result = $this->workflowService->rejectRequest(
                $studentRequest,
                Auth::id(),
                $request->rejection_reason,
                $request->comments
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
                'message' => 'Failed to reject request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the approval workflow for a student request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getWorkflow($id)
    {
        $studentRequest = StudentRequest::findOrFail($id);
        
        // Check if the user is authorized to view this request
        $this->authorize('view', $studentRequest);
        
        try {
            $workflow = $this->workflowService->getWorkflowDetails($studentRequest);
            
            return response()->json([
                'success' => true,
                'data' => $workflow
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve workflow',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get requests pending approval for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingApprovals()
    {
        $user = Auth::user();
        
        $pendingRequests = StudentRequest::where('current_approver_id', $user->id)
                                      ->where('status', 'in_review')
                                      ->with(['user'])
                                      ->orderBy('created_at', 'asc')
                                      ->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $pendingRequests
        ]);
    }

    /**
     * Get the request history for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function history()
    {
        $user = Auth::user();
        
        $requests = StudentRequest::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    /**
     * Upload an attachment to a student request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadAttachment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // Max 10MB
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $studentRequest = StudentRequest::findOrFail($id);
        
        // Check if the user is authorized to add attachments to this request
        $this->authorize('addAttachment', $studentRequest);
        
        try {
            $file = $request->file('file');
            $path = $file->store('request_attachments');
            
            $attachment = DocumentAttachment::create([
                'attachable_type' => 'App\Models\StudentRequest',
                'attachable_id' => $studentRequest->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $request->description,
                'uploaded_by' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Attachment uploaded successfully',
                'data' => $attachment
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload attachment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an attachment from a student request.
     *
     * @param  int  $requestId
     * @param  int  $attachmentId
     * @return \Illuminate\Http\Response
     */
    public function deleteAttachment($requestId, $attachmentId)
    {
        $studentRequest = StudentRequest::findOrFail($requestId);
        $attachment = DocumentAttachment::findOrFail($attachmentId);
        
        // Check if the attachment belongs to the request
        if ($attachment->attachable_type !== 'App\Models\StudentRequest' || 
            $attachment->attachable_id !== $studentRequest->id) {
            return response()->json([
                'success' => false,
                'message' => 'The attachment does not belong to this request.'
            ], 422);
        }
        
        // Check if the user is authorized to delete attachments from this request
        $this->authorize('deleteAttachment', $studentRequest);
        
        try {
            // Delete the file from storage
            Storage::delete($attachment->file_path);
            
            // Delete the attachment record
            $attachment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Attachment deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attachment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
