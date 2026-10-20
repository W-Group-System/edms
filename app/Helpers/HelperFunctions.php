<?php
use App\CopyApprover;
use App\DepartmentApprover;
use App\DepartmentDco;
use App\Memorandum;
use App\PreAssessmentApprover;
use App\ProcessReviewRequest;
use App\RequestApprover;
use App\SupportingDocument;

function memorandum_approval_count()
{
    $user = auth()->user();

    if (!$user || !in_array($user->role, ['Administrator', 'Document Control Officer'])) {
        return 0;
    }

    $query = Memorandum::where('final_status', 'Pending');

    if ($user->role === 'Document Control Officer') {
        $query->whereHas('departmentDcos', function ($departmentDcoQuery) use ($user) {
            $departmentDcoQuery->where('user_id', $user->id);
        });
    }

    return $query->count();
}

function supporting_document_approval_count()
{
    $user = auth()->user();

    if (!$user || !in_array($user->role, ['Administrator', 'Document Control Officer'])) {
        return 0;
    }

    $query = SupportingDocument::where('status', 'Pending');

    if ($user->role === 'Document Control Officer') {
        $query->whereHas('supporting_document_dept', function ($supportingDocumentDepartmentQuery) use ($user) {
            $supportingDocumentDepartmentQuery->whereHas('department.dco', function ($departmentDcoQuery) use ($user) {
                $departmentDcoQuery->where('user_id', $user->id);
            });
        });
    }

    return $query->count();
}

function copy_approver_count(){
    $user = auth()->user();
    $count = CopyApprover::where('user_id', $user->id)->where('status', 'Pending')->count()
        + RequestApprover::where('user_id', $user->id)->where('status', 'Pending')->count()
        + ProcessReviewRequest::where('user_id', $user->id)
            ->whereIn(
                'status',
                $user->role === 'Document Control Officer'
                    ? ['Returned', 'Completed']
                    : ['Returned', 'Completed', 'Declined']
            )
            ->count();

    if ($user->role === 'Document Control Officer') {
        $departmentIds = DepartmentDco::where('user_id', $user->id)->pluck('department_id');
    } elseif ($user->role === 'Department Head') {
        $departmentIds = DepartmentApprover::where('user_id', $user->id)->pluck('department_id');
    }

    if (isset($departmentIds)) {
        $statuses = $user->role === 'Document Control Officer'
            ? ['Pending', 'Returned']
            : ['Pending', 'Returned', 'Declined'];

        $count += ProcessReviewRequest::whereIn('status', $statuses)
            ->whereIn('department_id', $departmentIds)
            ->count();
    }

    return $count;
}

function pre_assessment_count(){
    $pre_assessment = PreAssessmentApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->where('status','Pending')->count();
    $count = $pre_assessment;
    return $count;
}

function calculateTargetDate($createdAt, $departmentHeadApproved, $typeOfDocument)

{
    $date_push = '2024-08-22';
    $createdDate = date('Y-m-d', strtotime($createdAt));

    if (in_array($typeOfDocument, ["FORM", "ANNEX", "TEMPLATE"])) {
        if ($date_push > $createdDate) {
            return date('Y-m-d', strtotime("+7 days", strtotime($createdDate)));
        } else {
            return $departmentHeadApproved != null
                ? date('Y-m-d', strtotime("+7 days", strtotime($departmentHeadApproved)))
                : date('Y-m-d');
        }
    } else {
        if ($date_push > $createdDate) {
            return date('Y-m-d', strtotime("+1 month", strtotime($createdDate)));
        } else {
            return $departmentHeadApproved != null
                ? date('Y-m-d', strtotime("+1 month", strtotime($departmentHeadApproved)))
                : date('Y-m-d');
        }
    }
    
}