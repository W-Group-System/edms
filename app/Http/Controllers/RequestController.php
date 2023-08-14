<?php

namespace App\Http\Controllers;
use App\Document;
use App\Department;
use App\Company;
use App\DepartmentApprover;
use App\CopyRequest;
use App\ChangeRequest;
use App\DocumentAttachment;
use App\CopyApprover;
use App\RequestApprover;
use App\ObsoleteAttachment;
use App\Obsolete;
use App\DocumentType;
use App\User;



use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       
        $requests = CopyRequest::orderBy('id','desc')->get();
        if(auth()->user()->role == "User")
        {
            $requests = CopyRequest::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Document Control Officer")
        {
            $requests = CopyRequest::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Department Head")
        {
            $requests = CopyRequest::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Documents and Records Controller")
        {
            $requests = CopyRequest::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
        }
        return view('requests',
        array(
            'requests' =>  $requests,
        ));
    }
    public function changeRequests()
    {
        //
        $departments = Department::where('id',auth()->user()->department_id)->where('status',null)->get();
        $companies = Company::where('status',null)->get();
        $document_types = DocumentType::get();
        $approvers = DepartmentApprover::where('department_id',auth()->user()->department_id)->get();
        $requests = ChangeRequest::orderBy('id','desc')->get();
        if(auth()->user()->role == "User")
        {
            $requests = ChangeRequest::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Document Control Officer")
        {
            $requests = ChangeRequest::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Department Head")
        {
            $requests = ChangeRequest::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Documents and Records Controller")
        {
            $requests = ChangeRequest::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
        }
        return view('change_requests',
        
        array(
            'requests' =>  $requests,
            
            'companies' =>  $companies,
            'departments' =>  $departments,
            'approvers' =>  $approvers,
            'document_types' =>  $document_types,
        ));
    }
    public function forApproval()
    {
        //
        $copy_for_approvals = CopyApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->get();
        $change_for_approvals = RequestApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->get();

        return view('for_approval',
        array(
           'copy_for_approvals' => $copy_for_approvals,
           'change_for_approvals' => $change_for_approvals,
        ));
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
        //
        $document = Document::findOrfail($request->id);
        $original_pdf = DocumentAttachment::where('document_id',$request->id)->where('type','pdf_copy')->first();
        $original_soft_copy = DocumentAttachment::where('document_id',$request->id)->where('type','soft_copy')->first();

      
        $changeRequest = new ChangeRequest;
        $changeRequest->request_type = $request->request_type;
        $changeRequest->effective_date = $request->effective_date;
        $changeRequest->department_id = auth()->user()->department_id;
        $changeRequest->company_id = auth()->user()->company_id;
        $changeRequest->user_id = auth()->user()->id;
        $changeRequest->type_of_document = $document->category;
        $changeRequest->document_id = $request->id;
        $changeRequest->change_request = $request->description;
        $changeRequest->indicate_clause = $request->from_clause;
        $changeRequest->indicate_changes = $request->to_changes;
        $changeRequest->link_draft = $request->draft_link;
        $changeRequest->status = "Pending";
        $changeRequest->level = 1;
        $changeRequest->control_code = $request->control_code;
        $changeRequest->title = $request->title;
        $changeRequest->revision = $request->revision;
        if($original_pdf != null)
        {
            $changeRequest->original_attachment_pdf = $original_pdf->attachment;
        }
        if($original_soft_copy != null)
        {
            $changeRequest->original_attachment_soft_copy = $original_soft_copy->attachment;
        }
        $changeRequest->save();
    
        $approvers = DepartmentApprover::where('department_id',$document->department_id)->orderBy('level','asc')->get();
        foreach($approvers as $approver)
        {
            $copy_approver = new RequestApprover;
            $copy_approver->change_request_id = $changeRequest->id;
            $copy_approver->user_id = $approver->user_id;
           
            if($approver->level == 1)
            {
                $copy_approver->status = "Pending";
                $copy_approver->start_date = date('Y-m-d');
            }
            else
            {
                $copy_approver->status = "Waiting";
               
            }
            $copy_approver->level = $approver->level;
            $copy_approver->save();
        }

        Alert::success('Successfully Submitted')->persistent('Dismiss');
        return redirect('/change-requests');


    }
    public function new_request(Request $request)
    {
        //

      
        $changeRequest = new ChangeRequest;
        $changeRequest->request_type = $request->request_type;
        $changeRequest->effective_date = $request->effective_date;
        $changeRequest->department_id = $request->department;
        $changeRequest->company_id = $request->company;
        $changeRequest->title = $request->title;
        $changeRequest->user_id = auth()->user()->id;
        $changeRequest->type_of_document = $request->category;
        $changeRequest->change_request = $request->description;
        $changeRequest->link_draft = $request->draft_link;
        $changeRequest->status = "Pending";
        $changeRequest->level = 1;
        $changeRequest->save();
    
        $approvers = DepartmentApprover::where('department_id',auth()->user()->department_id)->orderBy('level','asc')->get();
        foreach($approvers as $approver)
        {
            $copy_approver = new RequestApprover;
            $copy_approver->change_request_id = $changeRequest->id;
            $copy_approver->user_id = $approver->user_id;
           
            if($approver->level == 1)
            {
                $copy_approver->status = "Pending";
                $copy_approver->start_date = date('Y-m-d');
            }
            else
            {
                $copy_approver->status = "Waiting";
               
            }
            $copy_approver->level = $approver->level;
            $copy_approver->save();
        }

        Alert::success('Successfully Submitted')->persistent('Dismiss');
        return redirect('/change-requests');


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
    public function action(Request $request,$id)
    {
        $copyRequestApprover = RequestApprover::findOrfail($id);
        $copyRequestApprover->status = $request->action;
        $copyRequestApprover->remarks = $request->remarks;
        $copyRequestApprover->save();

        $copyApprover = RequestApprover::where('change_request_id',$copyRequestApprover->change_request_id)->where('status','Waiting')->orderBy('level','asc')->first();
        $copyRequest = ChangeRequest::findOrfail($copyRequestApprover->change_request_id);

        
        if($request->action == "Approved")
        {
            if(auth()->user()->role == "Document Control Officer")
            {
                if($request->has('soft_copy'))
                {
                    $attachment = $request->file('soft_copy');
                
                    $name = time() . '_' . $attachment->getClientOriginalName();
                    $attachment->move(public_path() . '/document_attachments/', $name);
                    $file_name = '/document_attachments/' . $name;
                    $copyRequest->soft_copy = $file_name;
                    $copyRequest->save();
                }
                if($request->has('pdf_copy'))
                {
                    $attachment = $request->file('pdf_copy');
                    $name = time() . '_' . $attachment->getClientOriginalName();
                    $attachment->move(public_path() . '/document_attachments/', $name);
                    $file_name = '/document_attachments/' . $name;
                    $copyRequest->pdf_copy = $file_name;
                    $copyRequest->save();
                }
                if($request->has('fillable_copy'))
                {
                    $attachment = $request->file('fillable_copy');
                    $name = time() . '_' . $attachment->getClientOriginalName();
                    $attachment->move(public_path() . '/document_attachments/', $name);
                    $file_name = '/document_attachments/' . $name;
                    $copyRequest->fillable_copy = $file_name;
                    $copyRequest->save();
                }
                
            }
            if($copyApprover == null)
            {
                if($copyRequest->request_type == "Revision")
                {
                    $document = Document::findOrfail($copyRequest->document_id);
                    $obsolete = new Obsolete;
                    $obsolete->document_id = $document->id;
                    $obsolete->control_code = $document->control_code;
                    $obsolete->company_id = $document->company_id;
                    $obsolete->department_id = $document->department_id;
                    $obsolete->title = $document->title;
                    $obsolete->category = $document->category;
                    $obsolete->other_category = $document->other_category;
                    $obsolete->user_id = $copyRequest->user_id;
                    $obsolete->version = $document->version;
                    $obsolete->save();

                    $attacments = DocumentAttachment::where('document_id',$document->id)->get();
                    foreach($attacments as $attach)
                    {
                        $obsolete_attach = new ObsoleteAttachment;
                        $obsolete_attach->obsolete_id = $obsolete->id;
                        $obsolete_attach->attachment = $attach->attachment;
                        $obsolete_attach->type = $attach->type;
                        $obsolete_attach->save();
                    }

                    $document->version = $document->version + 1;
                    $document->effective_date = $copyRequest->effective_date;
                    $document->user_id = $copyRequest->user_id;
                    $document->save();

 
                    $attac = DocumentAttachment::where('document_id',$document->id)->delete();
                    $new_attach = new DocumentAttachment;
                    $new_attach->document_id = $document->id; 
                    $new_attach->type = "soft_copy"; 
                    $new_attach->attachment = $copyRequest->soft_copy; 
                    $new_attach->save(); 

                    $new_attach = new DocumentAttachment;
                    $new_attach->document_id = $document->id; 
                    $new_attach->type = "pdf_copy"; 
                    $new_attach->attachment = $copyRequest->pdf_copy; 
                    $new_attach->save(); 

                    if($copyRequest->fillable_copy != null)
                    {
                        $new_attach = new DocumentAttachment;
                        $new_attach->document_id = $document->id; 
                        $new_attach->type = "fillable_copy"; 
                        $new_attach->attachment = $copyRequest->fillable_copy; 
                        $new_attach->save(); 
                    }

                }
                if($copyRequest->request_type == "Obsolete")
                {
                    $document = Document::findOrfail($copyRequest->document_id);
                    $document->status = "Obsolete";
                    $document->save();
                }
                if($copyRequest->request_type == "New")
                {
                   $company = Company::findOrFail($copyRequest->company_id);
                   $department = Department::findOrFail($copyRequest->department_id);
                   $type_of_doc = DocumentType::where('name',$copyRequest->type_of_document)->first();

                   $document_get_latest = Document::where('company_id',$copyRequest->company_id)->where('department_id',$copyRequest->department_id)->where('category',$copyRequest->type_of_document)->orderBy('id','desc')->first();
                   if($document_get_latest == null)
                   {
                        $code = $company->code."-".$type_of_doc->code."-".$department->code."-001";
                   }
                   else
                   {
                        $c = $document_get_latest->control_code;
                        $c = explode("-", $c);
                        $last_code = ((int)$c[count($c)-1])+1;
                        $code = $company->code."-".$type_of_doc->code."-".$department->code."-".str_pad($last_code, 3, '0', STR_PAD_LEFT);
                   }
                   $new_document = new Document;
                   $new_document->company_id = $copyRequest->company_id;
                   $new_document->department_id = $copyRequest->department_id;
                   $new_document->title = $copyRequest->title;
                   $new_document->category = $copyRequest->type_of_document;
                   $new_document->effective_date = date('Y-m-d');
                   $new_document->user_id = $copyRequest->user_id;
                   $new_document->version = 0;
                   $new_document->control_code = $code;
                   $new_document->save();

                   $copyRequest->document_id = $new_document->id;
                   $copyRequest->control_code = $code;
                   $copyRequest->revision = 0;

                   $new_attach = new DocumentAttachment;
                    $new_attach->document_id = $new_document->id; 
                    $new_attach->type = "soft_copy"; 
                    $new_attach->attachment = $copyRequest->soft_copy; 
                    $new_attach->save(); 

                    $new_attach = new DocumentAttachment;
                    $new_attach->document_id = $new_document->id; 
                    $new_attach->type = "pdf_copy"; 
                    $new_attach->attachment = $copyRequest->pdf_copy; 
                    $new_attach->save(); 

                    if($copyRequest->fillable_copy != null)
                    {
                        $new_attach = new DocumentAttachment;
                        $new_attach->document_id = $new_document->id; 
                        $new_attach->type = "fillable_copy"; 
                        $new_attach->attachment = $copyRequest->fillable_copy; 
                        $new_attach->save(); 
                    }
                }
                $copyRequest->status = "Approved";
                $copyRequest->save();
            }
            else
            {
                $copyApprover->start_date = date('Y-m-d');
                $copyApprover->status = "Pending";
                $copyApprover->save();
                $copyRequest->level = $copyRequest->level+1;
                $copyRequest->save();
                
            }
            Alert::success('Successfully Approved')->persistent('Dismiss');
            return back();
        }
        else
        {
            $copyRequest->status = "Declined";
            $copyRequest->save(); 
            Alert::success('Successfully Declined')->persistent('Dismiss');
            return back();
        }
    
    }
    public function changeReports(Request $request)
    {
        $search = $request->yearmonth;
        if($search)
        {
        $year = date('Y',strtotime($search."-01"));
        $month = date('m',strtotime($search."-01"));
        $requests = ChangeRequest::whereYear('created_at', '=', $year)
        ->whereMonth('created_at', '=', $month)->orderBy('id','desc')->get();
        }
        else
        {
            $requests = ChangeRequest::orderBy('id','desc')->get();
        }
        return view('change_reports',
        array(
            'requests' =>  $requests,
            'search' =>  $search,
        ));
    }
    public function docReports(Request $request)
    {
        $dco = $request->dco;
        $dcos = User::where('role','Document Control Officer')->get();
        $requests = ChangeRequest::orderBy('id','desc')->get();
        if($dco != null)
        {
          
            $user = User::where('id',$request->dco)->first();
            $requests = ChangeRequest::whereIn('department_id',($user->dco)->pluck('department_id')->toArray())->orderBy('id','desc')->get();
        }
        

        return view('dcoReports',
        
        array(
            'requests' =>  $requests,
            'dcos' =>  $dcos,
            'dco' =>  $dco,
        ));
        
    }
}
