<?php

namespace App\Http\Controllers;

use App\ChangeRequest;
use App\Company;
use App\Department;
use App\DepartmentApprover;
use App\DepartmentDco;
use App\DocumentType;
use App\PreAssessment;
use App\PreAssessmentApprover;
use App\RequestApprover;
use App\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PreAssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::where('id',auth()->user()->department_id)->where('status',null)->get();
        $companies = Company::where('status',null)->get();
        $document_types = DocumentType::get();
        $approver = DepartmentDco::where('department_id',auth()->user()->department_id)->first();
        $pre_assessment = PreAssessment::orderBy('id','desc')->get();
        
        return view('pre_assessment',
            array(
                'departments' => $departments,
                'companies' => $companies,
                'document_types' => $document_types,
                'approver' => $approver,
                'pre_assessment' => $pre_assessment
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function approve(Request $request, $id)
    {
        // dd($request->all());
        $preAssessment = PreAssessment::findOrFail($id);
        
        if ($request->action == "Approved")
        {
            $preAssessmentApprover = PreAssessmentApprover::where('pre_assessment_id', $id)->first();
            $preAssessmentApprover->status = "Approved";
            $preAssessmentApprover->remarks = $request->remarks;
            $preAssessmentApprover->save();

            $preAssessment->status = $request->action;
            $preAssessment->save();

            $changeRequest = ChangeRequest::where('title', $preAssessment->title)->where('request_type', $preAssessment->request_type)->where('type_of_document', $preAssessment->type_of_document)->orderBy('id', 'desc')->first();
            
            if ($changeRequest == null)
            {
                $changeRequest = new ChangeRequest;
                $changeRequest->request_type = $preAssessment->request_type;
                $changeRequest->effective_date = $preAssessment->effective_date;
                $changeRequest->department_id = $preAssessment->department_id;
                $changeRequest->user_id = $preAssessment->user_id;
                $changeRequest->type_of_document = $preAssessment->type_of_document;
                $changeRequest->document_id = $preAssessment->document_id;
                $changeRequest->change_request = $preAssessment->change_request;
                $changeRequest->reason_for_changes = $preAssessment->reason_for_changes;
                $changeRequest->link_draft = $preAssessment->link_draft;
                $changeRequest->status = "Pending";
                $changeRequest->level = 1;
                $changeRequest->company_id = $preAssessment->company_id;
                $changeRequest->control_code = $preAssessment->control_code;
                $changeRequest->title = $preAssessment->title;
                $changeRequest->revision = $preAssessment->revision;
                $changeRequest->original_attachment_pdf = $preAssessment->original_attachment_pdf;
                $changeRequest->original_attachment_soft_copy = $preAssessment->original_attachment_soft_copy;
                $changeRequest->pdf_copy = $preAssessment->pdf_copy;
                $changeRequest->soft_copy = $preAssessment->soft_copy;
                $changeRequest->supporting_documents = $request->supporting_documents;
                $changeRequest->save();
            }

            $departmentApprover = DepartmentApprover::where('department_id', auth()->user()->department_id)->get();
            foreach($departmentApprover as $approver)
            {
                $requestApprover = new RequestApprover;
                $requestApprover->change_request_id = $changeRequest->id;
                $requestApprover->user_id = $approver->user_id;
                $requestApprover->level = $approver->level;

                if($approver->level == 1)
                {
                    $requestApprover->status = "Pending";
                    $requestApprover->start_date = date('Y-m-d');
                }
                else
                {
                    $requestApprover->status = "Waiting";
                
                }
                $requestApprover->level = $approver->level;

                $requestApprover->save();
            }
        }
        elseif($request->action == "Declined")
        {
            $preAssessmentApprover = PreAssessmentApprover::where('pre_assessment_id', $id)->first();
            $preAssessmentApprover->status = "Declined";
            $preAssessmentApprover->remarks = $request->remarks;
            $preAssessmentApprover->save();

            $preAssessment->status = $request->action;
            $preAssessment->save();

            $changeRequest = ChangeRequest::where('title', $preAssessment->title)->where('request_type', $preAssessment->request_type)->where('type_of_document', $preAssessment->type_of_document)->first();
            $changeRequest->delete();
        }

        Alert::success('Successfully Submitted')->persistent('Dismiss');
        return back();
    }
}
