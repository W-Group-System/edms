<?php

namespace App\Http\Controllers;
use App\CopyRequest;
use App\CopyApprover;
use Illuminate\Http\Request;

use RealRashid\SweetAlert\Facades\Alert;
class CopyController extends Controller
{
    //
    public function store(Request $request)
    {
        // dd($request->all());
        $copy_request = new CopyRequest;
        $copy_request->type_of_document = $request->type_of_document;
        $copy_request->document_id = $request->id;
        $copy_request->control_code = $request->control_code;
        $copy_request->title = $request->title;
        $copy_request->revision = $request->revision;
        $copy_request->user_id = auth()->user()->id;
        $copy_request->copy_count = $request->name;
        $copy_request->date_needed = $request->date_needed;
        $copy_request->department_id = auth()->user()->department_id;
        $copy_request->company_id = auth()->user()->company_id;
        $copy_request->status = "Pending";
        $copy_request->level = 1;
        $copy_request->save();

        $copy_approver = new CopyApprover;
        $copy_approver->copy_request_id = $copy_request->id;
        $copy_approver->user_id = $request->immediate_head;
        $copy_approver->status = "Pending";
        $copy_approver->start_date = date('Y-m-d');
        $copy_approver->level = 1;
        $copy_approver->save();

        $copy_approver = new CopyApprover;
        $copy_approver->copy_request_id = $copy_request->id;
        $copy_approver->user_id = $request->drc;
        $copy_approver->status = "Waiting";
        $copy_approver->level = 2;
        $copy_approver->save();

        $copy_approver = new CopyApprover;
        $copy_approver->copy_request_id = $copy_request->id;
        $copy_approver->user_id = $request->drc_head;
        $copy_approver->status = "Waiting";
        $copy_approver->level = 3;
        $copy_approver->save();
        
        Alert::success('Successfully Requested')->persistent('Dismiss');
        return redirect('/request');
    }
}
