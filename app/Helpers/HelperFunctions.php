<?php
use App\CopyApprover;
use App\RequestApprover;


function copy_approver_count(){
    $copy_for_approvals = CopyApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->where('status','Pending')->count();
    $change_for_approvals = RequestApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->where('status','Pending')->count();
    $count = $copy_for_approvals+$change_for_approvals;
    return $count;
}