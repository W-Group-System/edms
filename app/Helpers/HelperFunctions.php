<?php
use App\CopyApprover;
use App\PreAssessmentApprover;
use App\RequestApprover;


function copy_approver_count(){
    $copy_for_approvals = CopyApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->where('status','Pending')->count();
    $change_for_approvals = RequestApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->where('status','Pending')->count();
    $count = $copy_for_approvals+$change_for_approvals;
    return $count;
}

function pre_assessment_count(){
    $pre_assessment = PreAssessmentApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->where('status','Pending')->count();
    $count = $pre_assessment;
    return $count;
}