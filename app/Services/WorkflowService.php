<?php

namespace App\Services;

use App\Models\StudentRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WorkflowService
{
    /**
     * Initialize the approval workflow for a student request.
     *
     * @param  \App\Models\StudentRequest  $request
     * @return array
     */
    public function initializeWorkflow(StudentRequest $request)
    {
        // Get the workflow based on the request type
        $workflow = $this->getWorkflowByRequestType($request->request_type);
        
        if (empty($workflow)) {
            return [
                'success' => false,
                'message' => 'No workflow defined for this request type.'
            ];
        }
        
        try {
            // Initialize the workflow
            $request->approval_workflow = $workflow;
            $request->current_approver_id = $workflow[0]['approver_id'];
            $request->status = 'in_review';
            $request->approval_history = [];
            $request->save();
            
            return [
                'success' => true,
                'message' => 'Workflow initialized successfully.',
                'data' => [
                    'workflow' => $workflow,
                    'current_approver' => $workflow[0]['approver_name'],
                    'current_step' => $workflow[0]['step_name']
                ]
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to initialize workflow: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get the workflow based on the request type.
     *
     * @param  string  $requestType
     * @return array
     */
    private function getWorkflowByRequestType($requestType)
    {
        // In a real system, this would be fetched from a database or configuration
        // For now, we'll define some sample workflows
        
        $workflows = [
            'course_withdrawal' => [
                [
                    'step_name' => 'Academic Advisor Review',
                    'approver_id' => $this->getAdvisorId(),
                    'approver_name' => 'Academic Advisor',
                    'description' => 'Review by academic advisor to ensure the withdrawal is in the student\'s best interest.'
                ],
                [
                    'step_name' => 'Department Chair Review',
                    'approver_id' => $this->getDepartmentChairId(),
                    'approver_name' => 'Department Chair',
                    'description' => 'Review by department chair to approve the course withdrawal.'
                ],
                [
                    'step_name' => 'Registrar Approval',
                    'approver_id' => $this->getRegistrarId(),
                    'approver_name' => 'Registrar',
                    'description' => 'Final approval by the registrar to process the course withdrawal.'
                ]
            ],
            'grade_change' => [
                [
                    'step_name' => 'Instructor Review',
                    'approver_id' => $this->getInstructorId(),
                    'approver_name' => 'Course Instructor',
                    'description' => 'Review by the course instructor to verify the grade change request.'
                ],
                [
                    'step_name' => 'Department Chair Review',
                    'approver_id' => $this->getDepartmentChairId(),
                    'approver_name' => 'Department Chair',
                    'description' => 'Review by department chair to approve the grade change.'
                ],
                [
                    'step_name' => 'Registrar Approval',
                    'approver_id' => $this->getRegistrarId(),
                    'approver_name' => 'Registrar',
                    'description' => 'Final approval by the registrar to process the grade change.'
                ]
            ],
            'retake_exam' => [
                [
                    'step_name' => 'Instructor Review',
                    'approver_id' => $this->getInstructorId(),
                    'approver_name' => 'Course Instructor',
                    'description' => 'Review by the course instructor to approve the exam retake.'
                ],
                [
                    'step_name' => 'Department Chair Review',
                    'approver_id' => $this->getDepartmentChairId(),
                    'approver_name' => 'Department Chair',
                    'description' => 'Review by department chair to approve the exam retake.'
                ]
            ],
            'leave_of_absence' => [
                [
                    'step_name' => 'Academic Advisor Review',
                    'approver_id' => $this->getAdvisorId(),
                    'approver_name' => 'Academic Advisor',
                    'description' => 'Review by academic advisor to ensure the leave of absence is in the student\'s best interest.'
                ],
                [
                    'step_name' => 'Department Chair Review',
                    'approver_id' => $this->getDepartmentChairId(),
                    'approver_name' => 'Department Chair',
                    'description' => 'Review by department chair to approve the leave of absence.'
                ],
                [
                    'step_name' => 'Dean Approval',
                    'approver_id' => $this->getDeanId(),
                    'approver_name' => 'Dean',
                    'description' => 'Final approval by the dean to grant the leave of absence.'
                ]
            ],
            'program_change' => [
                [
                    'step_name' => 'Academic Advisor Review',
                    'approver_id' => $this->getAdvisorId(),
                    'approver_name' => 'Academic Advisor',
                    'description' => 'Review by academic advisor to ensure the program change is in the student\'s best interest.'
                ],
                [
                    'step_name' => 'Current Department Chair Review',
                    'approver_id' => $this->getDepartmentChairId(),
                    'approver_name' => 'Current Department Chair',
                    'description' => 'Review by current department chair to approve the program change.'
                ],
                [
                    'step_name' => 'New Department Chair Review',
                    'approver_id' => $this->getNewDepartmentChairId(),
                    'approver_name' => 'New Department Chair',
                    'description' => 'Review by new department chair to accept the student into the program.'
                ],
                [
                    'step_name' => 'Registrar Approval',
                    'approver_id' => $this->getRegistrarId(),
                    'approver_name' => 'Registrar',
                    'description' => 'Final approval by the registrar to process the program change.'
                ]
            ]
        ];
        
        // Default workflow for other request types
        $defaultWorkflow = [
            [
                'step_name' => 'Academic Advisor Review',
                'approver_id' => $this->getAdvisorId(),
                'approver_name' => 'Academic Advisor',
                'description' => 'Review by academic advisor.'
            ],
            [
                'step_name' => 'Department Chair Review',
                'approver_id' => $this->getDepartmentChairId(),
                'approver_name' => 'Department Chair',
                'description' => 'Review by department chair.'
            ]
        ];
        
        return $workflows[$requestType] ?? $defaultWorkflow;
    }
    
    /**
     * Get the ID of the academic advisor.
     *
     * @return int
     */
    private function getAdvisorId()
    {
        // In a real system, this would be fetched from a database
        // For now, we'll return a placeholder ID
        return 2; // Assuming ID 2 is an academic advisor
    }
    
    /**
     * Get the ID of the department chair.
     *
     * @return int
     */
    private function getDepartmentChairId()
    {
        // In a real system, this would be fetched from a database
        // For now, we'll return a placeholder ID
        return 3; // Assuming ID 3 is a department chair
    }
    
    /**
     * Get the ID of the new department chair.
     *
     * @return int
     */
    private function getNewDepartmentChairId()
    {
        // In a real system, this would be fetched from a database
        // For now, we'll return a placeholder ID
        return 4; // Assuming ID 4 is another department chair
    }
    
    /**
     * Get the ID of the registrar.
     *
     * @return int
     */
    private function getRegistrarId()
    {
        // In a real system, this would be fetched from a database
        // For now, we'll return a placeholder ID
        return 5; // Assuming ID 5 is a registrar
    }
    
    /**
     * Get the ID of the dean.
     *
     * @return int
     */
    private function getDeanId()
    {
        // In a real system, this would be fetched from a database
        // For now, we'll return a placeholder ID
        return 6; // Assuming ID 6 is a dean
    }
    
    /**
     * Get the ID of the instructor.
     *
     * @return int
     */
    private function getInstructorId()
    {
        // In a real system, this would be fetched from a database
        // For now, we'll return a placeholder ID
        return 7; // Assuming ID 7 is an instructor
    }
    
    /**
     * Approve a student request.
     *
     * @param  \App\Models\StudentRequest  $request
     * @param  int  $approverId
     * @param  string|null  $comments
     * @return array
     */
    public function approveRequest(StudentRequest $request, $approverId, $comments = null)
    {
        // Check if the request is in a state that can be approved
        if ($request->status !== 'in_review') {
            return [
                'success' => false,
                'message' => 'This request cannot be approved because it is not currently in review.'
            ];
        }
        
        // Check if the current approver matches the provided approver ID
        if ($request->current_approver_id != $approverId) {
            return [
                'success' => false,
                'message' => 'You are not authorized to approve this request at this time.'
            ];
        }
        
        try {
            // Get the current approval history or initialize an empty array
            $approvalHistory = $request->approval_history ?? [];
            
            // Add the current approval to the history
            $approvalHistory[] = [
                'approver_id' => $approverId,
                'action' => 'approved',
                'comments' => $comments,
                'timestamp' => now()->toDateTimeString(),
            ];
            
            // Update the approval history
            $request->approval_history = $approvalHistory;
            
            // Check if this is the final approval in the workflow
            $workflow = $request->approval_workflow ?? [];
            $currentApproverIndex = -1;
            
            foreach ($workflow as $index => $step) {
                if ($step['approver_id'] == $request->current_approver_id) {
                    $currentApproverIndex = $index;
                    break;
                }
            }
            
            if ($currentApproverIndex !== -1 && isset($workflow[$currentApproverIndex + 1])) {
                // Move to the next approver in the workflow
                $nextStep = $workflow[$currentApproverIndex + 1];
                $request->status = 'in_review';
                $request->current_approver_id = $nextStep['approver_id'];
                
                $message = 'Request approved. Moved to next step: ' . $nextStep['step_name'];
                $data = [
                    'next_step' => $nextStep['step_name'],
                    'next_approver' => $nextStep['approver_name']
                ];
            } else {
                // This is the final approval
                $request->status = 'approved';
                $request->current_approver_id = null;
                
                $message = 'Request fully approved.';
                $data = [
                    'final_approval' => true
                ];
                
                // Process the approved request (e.g., update related records)
                $this->processApprovedRequest($request);
            }
            
            $request->save();
            
            return [
                'success' => true,
                'message' => $message,
                'data' => array_merge([
                    'request' => $request
                ], $data)
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to approve request: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Reject a student request.
     *
     * @param  \App\Models\StudentRequest  $request
     * @param  int  $approverId
     * @param  string  $rejectionReason
     * @param  string|null  $comments
     * @return array
     */
    public function rejectRequest(StudentRequest $request, $approverId, $rejectionReason, $comments = null)
    {
        // Check if the request is in a state that can be rejected
        if ($request->status !== 'in_review') {
            return [
                'success' => false,
                'message' => 'This request cannot be rejected because it is not currently in review.'
            ];
        }
        
        // Check if the current approver matches the provided approver ID
        if ($request->current_approver_id != $approverId) {
            return [
                'success' => false,
                'message' => 'You are not authorized to reject this request at this time.'
            ];
        }
        
        try {
            // Get the current approval history or initialize an empty array
            $approvalHistory = $request->approval_history ?? [];
            
            // Add the rejection to the history
            $approvalHistory[] = [
                'approver_id' => $approverId,
                'action' => 'rejected',
                'comments' => $comments,
                'timestamp' => now()->toDateTimeString(),
            ];
            
            // Update the approval history and status
            $request->approval_history = $approvalHistory;
            $request->status = 'rejected';
            $request->rejection_reason = $rejectionReason;
            $request->current_approver_id = null;
            
            $request->save();
            
            return [
                'success' => true,
                'message' => 'Request rejected.',
                'data' => [
                    'request' => $request
                ]
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to reject request: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get detailed information about the workflow for a student request.
     *
     * @param  \App\Models\StudentRequest  $request
     * @return array
     */
    public function getWorkflowDetails(StudentRequest $request)
    {
        $workflow = $request->approval_workflow ?? [];
        $approvalHistory = $request->approval_history ?? [];
        
        // Map approval history to workflow steps
        $workflowDetails = [];
        
        foreach ($workflow as $index => $step) {
            $stepStatus = 'pending';
            $approverAction = null;
            $approverComments = null;
            $approvalTimestamp = null;
            
            // Check if this step has been completed in the approval history
            foreach ($approvalHistory as $historyItem) {
                if ($historyItem['approver_id'] == $step['approver_id']) {
                    $stepStatus = $historyItem['action']; // 'approved' or 'rejected'
                    $approverComments = $historyItem['comments'];
                    $approvalTimestamp = $historyItem['timestamp'];
                    break;
                }
            }
            
            // If the request is in review and this is the current step
            if ($request->status === 'in_review' && $request->current_approver_id == $step['approver_id']) {
                $stepStatus = 'in_review';
            }
            
            // If the request is rejected and a previous step was completed
            if ($request->status === 'rejected' && $stepStatus === 'pending') {
                $stepStatus = 'skipped';
            }
            
            $workflowDetails[] = [
                'step_name' => $step['step_name'],
                'approver_name' => $step['approver_name'],
                'approver_id' => $step['approver_id'],
                'description' => $step['description'],
                'status' => $stepStatus,
                'comments' => $approverComments,
                'timestamp' => $approvalTimestamp,
                'is_current_step' => ($request->current_approver_id == $step['approver_id'])
            ];
        }
        
        return [
            'request_id' => $request->id,
            'request_type' => $request->request_type,
            'status' => $request->status,
            'current_approver_id' => $request->current_approver_id,
            'workflow' => $workflowDetails,
            'rejection_reason' => $request->rejection_reason
        ];
    }
    
    /**
     * Process an approved request.
     *
     * @param  \App\Models\StudentRequest  $request
     * @return void
     */
    private function processApprovedRequest(StudentRequest $request)
    {
        // This method would contain the logic to process different types of approved requests
        // For example, updating enrollment records for course withdrawals, updating grades, etc.
        
        switch ($request->request_type) {
            case 'course_withdrawal':
                // Process course withdrawal
                $this->processCourseWithdrawal($request);
                break;
                
            case 'grade_change':
                // Process grade change
                $this->processGradeChange($request);
                break;
                
            case 'retake_exam':
                // Process exam retake
                $this->processExamRetake($request);
                break;
                
            case 'leave_of_absence':
                // Process leave of absence
                $this->processLeaveOfAbsence($request);
                break;
                
            case 'program_change':
                // Process program change
                $this->processProgramChange($request);
                break;
                
            default:
                // No specific processing for other request types
                break;
        }
    }
    
    /**
     * Process an approved course withdrawal request.
     *
     * @param  \App\Models\StudentRequest  $request
     * @return void
     */
    private function processCourseWithdrawal(StudentRequest $request)
    {
        // In a real system, this would update the enrollment record
        // For now, we'll just log the action
        \Log::info('Processing course withdrawal for request ID: ' . $request->id);
    }
    
    /**
     * Process an approved grade change request.
     *
     * @param  \App\Models\StudentRequest  $request
     * @return void
     */
    private function processGradeChange(StudentRequest $request)
    {
        // In a real system, this would update the grade record
        // For now, we'll just log the action
        \Log::info('Processing grade change for request ID: ' . $request->id);
    }
    
    /**
     * Process an approved exam retake request.
     *
     * @param  \App\Models\StudentRequest  $request
     * @return void
     */
    private function processExamRetake(StudentRequest $request)
    {
        // In a real system, this would update exam records
        // For now, we'll just log the action
        \Log::info('Processing exam retake for request ID: ' . $request->id);
    }
    
    /**
     * Process an approved leave of absence request.
     *
     * @param  \App\Models\StudentRequest  $request
     * @return void
     */
    private function processLeaveOfAbsence(StudentRequest $request)
    {
        // In a real system, this would update student status
        // For now, we'll just log the action
        \Log::info('Processing leave of absence for request ID: ' . $request->id);
    }
    
    /**
     * Process an approved program change request.
     *
     * @param  \App\Models\StudentRequest  $request
     * @return void
     */
    private function processProgramChange(StudentRequest $request)
    {
        // In a real system, this would update student program
        // For now, we'll just log the action
        \Log::info('Processing program change for request ID: ' . $request->id);
    }
}
