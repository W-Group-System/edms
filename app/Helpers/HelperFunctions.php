<?php
use App\CopyApprover;
use App\DepartmentApprover;
use App\DepartmentDco;
use App\PreAssessmentApprover;
use App\ProcessReviewRequest;
use App\RequestApprover;


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