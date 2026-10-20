<?php

namespace App\Http\Controllers;

use App\PrrDocumentsCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Company;
use App\Department;
use App\DocumentType;
use App\DepartmentApprover;
use App\DepartmentDco;
use App\ProcessReviewRequest;
use App\PrrDocumentsDepartment;
use App\TransactionHistory;
use App\Document;
use App\ChangeRequest;
use RealRashid\SweetAlert\Facades\Alert;
class ProcessReviewController extends Controller
{
    
    public function index()
    {
        $prr = ProcessReviewRequest::with([
            'transactionHistories.user',
            'changeRequest',
            'departmentDcos.user',
        ]);
        $user = auth()->user();
        $role = $user->role;
        $hasRequestAccess = $role === 'Administrator'
            || $role === 'Document Control Officer'
            || $role === 'Department Head'
            || ($role === 'User' && is_null($user->audit_role));

        if ($role === 'Document Control Officer') {
            $prr->whereIn('status', ['Pending', 'Ongoing', 'Completed', 'Returned', 'Cancelled']);
            $prr->whereIn('department_id', function ($query) {
                $query->select('department_id')
                    ->from('department_dcos')
                    ->where('user_id', auth()->id());
            });
        } elseif ($role === 'Department Head') {
            $prr->whereIn('status', ['Completed', 'Pending', 'Ongoing', 'Returned', 'Cancelled']);
            $prr->whereIn('department_id', function ($query) {
                $query->select('department_id')
                    ->from('department_approvers')
                    ->where('user_id', auth()->id());
            });
        } elseif ($role === 'User' && is_null($user->audit_role)) {
            $prr->where(function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->where('department_id', $user->department_id)
                        ->whereIn('status', ['Completed', 'Pending', 'Ongoing', 'Draft']);
                })->orWhere(function ($query) use ($user) {
                    $query->whereIn('status', ['Cancelled', 'Returned'])
                        ->where('user_id', $user->id);
                });
            });
        } elseif ($role !== 'Administrator') {
            $hasRequestAccess = false;
        }

        $prr->orderByDesc('id');

        // Fetch the results using ->get()
        $prr = $hasRequestAccess ? $prr->get() : collect();
        $countablePrr = $prr;
        $statusCards = ['Pending', 'Returned', 'Ongoing', 'Completed', 'Cancelled'];

        $departments = Department::where('id', auth()->user()->department_id)->where('status', null)->get();
        $companies = Company::where('status', null)->get();
        $documents = Document::with([
            'processOwner',
            'department.dep_head',
        ])
            ->where('department_id', auth()->user()->department_id)
            ->whereNull('status')
            ->orderBy('control_code')
            ->get();
        
        $changeRequests = ChangeRequest::with(['user', 'department', 'company'])
            ->whereIn('control_code', $documents->pluck('control_code')->filter()->unique())
            ->where('status', 'Approved')
            ->where('request_type', 'Revision')
            ->orderByDesc('created_at')
            ->get();

        $document_types = DocumentType::get();
        $department_approvers = DepartmentApprover::where('department_id', auth()->user()->department_id)->get();
        $department_dco = DepartmentDco::where('department_id', auth()->user()->department_id)->get();

        return view('process-review.index', [
            'prr' => $prr,
            'countablePrr' => $countablePrr,
            'statusCards' => $statusCards,
            'departments' => $departments,
            'companies' => $companies,
            'document_types' => $document_types,
            'department_approvers' => $department_approvers,
            'department_dco' => $department_dco,
            'documents' => $documents,
            'changeRequests' => $changeRequests,
        ]);
        // dd($prr);
    }

    public function store(Request $request)
    {
        // Validate submitted form data.
        $request->validate([
            'title'                  => 'required|string|max:255',
            'company'                => 'required|integer',
            'department'             => 'required|integer',
            'category'               => 'required|string',
            // 'link_draft'             => 'required|string',
            'supporting_document'    => 'nullable|file|mimes:pdf|max:10240',
            'type_of_request'        => 'required|in:New,Revision',
            'document_id'            => 'required_if:type_of_request,Revision|nullable|integer|exists:documents,id',
            'control_code'           => 'required_if:type_of_request,Revision|nullable|string|max:255',
            'reason_for_new_request' => 'required|string',
            'description'            => 'required|string',
            'submission_action'      => 'required|in:draft,submit',
        ]);

        try {
            $selectedDocument = null;
            if ($request->type_of_request === 'Revision') {
                $selectedDocument = Document::where('department_id', auth()->user()->department_id)
                    ->whereNull('status')
                    ->findOrFail($request->document_id);
                if ($request->control_code !== $selectedDocument->control_code) {
                    return redirect()->back()
                        ->withErrors(['control_code' => 'The control code does not match the selected document.'])
                        ->withInput();
                }
            }

            $prr = new ProcessReviewRequest;
            $prr->company_id = $request->company;
            $prr->department_id = $request->department;
            $prr->user_id = auth()->id();
            $prr->document_id = $selectedDocument ? $selectedDocument->id : null;
            $prr->control_code = $selectedDocument ? $selectedDocument->control_code : null;
            $prr->type_of_request = $request->type_of_request;
            $prr->type_of_document = $request->category;
            $prr->document_title = $request->title;
            $prr->document_description = $request->description;
            $prr->reason_of_request = $request->reason_for_new_request;
            // $prr->link_draft = $request->link_draft;
            $prr->remarks = $request->submission_action === 'draft' ? 'Draft' : 'Submitted';
            $prr->status = $request->submission_action === 'draft' ? 'Draft' : 'Pending';

            if ($request->hasFile('supporting_document')) {
                $file = $request->file('supporting_document');
                $uploadPath = public_path('prr_attachments');

                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move($uploadPath, $fileName);
                $prr->supporting_documents = '/prr_attachments/' . $fileName;
            }

            // Execute database transaction safely
            DB::transaction(function () use ($prr, $request) {
                $prr->save();
                
                $referencePrefix = 'QPPR-' . date('y') . '-';
                $lastReferenceNumber = ProcessReviewRequest::where('reference_num', 'like', $referencePrefix . '%')
                    ->orderByDesc('id')
                    ->value('reference_num');
                $sequence = $lastReferenceNumber
                    ? (int) substr($lastReferenceNumber, strlen($referencePrefix)) + 1
                    : 1;

                $prr->reference_num = $referencePrefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
                $prr->save();

                // // save department
                $prr_documents_department = new PrrDocumentsDepartment;
                $prr_documents_department->department_id = $request->department;
                $prr_documents_department->prr_document_id = $prr->id;
                $prr_documents_department->save();

                // // save company
                $prr_documents_company = new PrrDocumentsCompany;
                $prr_documents_company->company_id = $request->company;
                $prr_documents_company->prr_document_id = $prr->id;
                $prr_documents_company->save();

                if ($request->submission_action === 'submit') {
                    $history = new TransactionHistory;
                    $history->transaction_no = $prr->reference_num;
                    $history->action = 'Submitted';
                    $history->action_by = auth()->user()->id;
                    $history->remarks = 'N/A';
                    $history->save();
                }
            });

            $message = $prr->status === 'Draft'
                ? 'QPRR Saved as Draft'
                : 'QPRR Successfully Submitted';
            Alert::success($message)->persistent('Dismiss');

            return redirect('/process-review');
        } catch (\Throwable $exception) {
            Log::error('Failed to save process review request.', [
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);

            Alert::error('Unable to save the process review request. Please try again.')->persistent('Dismiss');

            return redirect()->back()->withInput();
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'title'                  => 'required|string|max:255',
            'company'                => 'required|integer',
            'department'             => 'required|integer',
            'category'               => 'required|string',
            // 'link_draft'             => 'required|string',
            'supporting_document'    => 'nullable|file|mimes:pdf|max:10240',
            'type_of_request'        => 'required|in:New,Revision',
            'document_id'            => 'required_if:type_of_request,Revision|nullable|integer|exists:documents,id',
            'control_code'           => 'required_if:type_of_request,Revision|nullable|string|max:255',
            'reason_for_new_request' => 'required|string',
            'description'            => 'required|string',
            'submission_action'      => 'required|in:draft,submit',
        ]);

        try {
            $prr = ProcessReviewRequest::findOrFail($id);
            $selectedDocument = null;
            if ($request->type_of_request === 'Revision') {
                $selectedDocument = Document::where('department_id', auth()->user()->department_id)
                    ->whereNull('status')
                    ->findOrFail($request->document_id);
                if ($request->control_code !== $selectedDocument->control_code) {
                    return redirect()->back()
                        ->withErrors(['control_code' => 'The control code does not match the selected document.'])
                        ->withInput();
                }
            }

            $prr->company_id = $request->company;
            $prr->department_id = $request->department;
            $prr->document_id = $selectedDocument ? $selectedDocument->id : null;
            $prr->control_code = $selectedDocument ? $selectedDocument->control_code : null;
            $prr->type_of_request = $request->type_of_request;
            $prr->type_of_document = $request->category;
            $prr->document_title = $request->title;
            $prr->document_description = $request->description;
            $prr->reason_of_request = $request->reason_for_new_request;
            // $prr->link_draft = $request->link_draft;
            $prr->remarks = $request->submission_action === 'draft' ? 'Draft' : 'Pending';
            $prr->status = $request->submission_action === 'draft' ? 'Draft' : 'Pending';

            if ($request->hasFile('supporting_document')) {
                $file = $request->file('supporting_document');
                $uploadPath = public_path('prr_attachments');

                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move($uploadPath, $fileName);
                $prr->supporting_documents = '/prr_attachments/' . $fileName;
            }

            $message = $prr->status === 'Draft'
                ? 'QPRR Saved as Draft'
                : 'QPRR Successfully Submitted';

            // $prr->save();

            if ($prr->save() && $request->submission_action === 'submit') {
                $history = new TransactionHistory;
                $history->transaction_no = $prr->reference_num;
                $history->action = 'Submitted';
                $history->action_by = auth()->user()->id;
                $history->remarks = 'N/A';
                $history->save();
            }

            Alert::success($message)->persistent('Dismiss');
            return redirect('/process-review');

        } catch (\Throwable $exception) {
            Log::error('Failed to update process review request.', [
                'user_id'   => auth()->id(),
                'exception' => $exception,
            ]);

            Alert::error('Unable to update the process review request. Please try again.')->persistent('Dismiss');
            return redirect()->back()->withInput();
        }
    }

    public function accept(Request $request, $id) 
    {
        $request ->validate([
            'remarks'               => 'required|string|max:255',
            'submission_action'     => 'required|in:return,submit'
        ]);

        try {
            $prr = ProcessReviewRequest::findOrFail($id);
            $prr->remarks = $request->remarks;
            $prr->status = $request->submission_action === 'return' ? 'Returned' : 'Ongoing';
    
            $message = $prr->status === 'Returned' 
                ? 'Returned Successfully to the Requestor'
                : 'QPPR Successfully Accepted';
            
            // $prr->save();
            if($prr->save()) {
                $history = new TransactionHistory;
                $history->transaction_no = $prr->reference_num;
                $history->action = $request->submission_action === 'return' ? 'Returned' : 'Ongoing';
                $history->action_by = auth()->user()->id;
                $history->remarks = $request->remarks;
                $history->save();
            }
            
            Alert::success($message)->persistent('Dismiss');
            return redirect('/process-review');

        }catch(\Throwable $exception) {
            Log::error('Failed to accept process review request.', [
                'user_id'   => auth()->id(),
                'exception' => $exception,
            ]);
            Alert::error('Unable to accept the process review request. Please try again.')->persistent('Dismiss');
            return redirect()->back()->withInput();   
        }
    }

    public function attachReport(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            // 'report_draft_link'  => 'required|string',
            'remarks'            => 'required|string|max:255',
        ]);

        try{
            $prr = ProcessReviewRequest::findOrFail($id);
            
            // $prr->report_draft_link = $request->report_draft_link;
            $prr->remarks = $request->remarks;
            
            if($request->hasFile('report_attachment')) {
                $file = $request->file('report_attachment');
                $uploadPath = public_path('prr_attachments');

                if(!file_exists($uploadPath)) {
                    mkdir($uploadPath,0755, true);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move($uploadPath, $fileName);
                $prr->report_attachment = '/prr_attachments/' . $fileName;

            }
            // $prr->save();
            if($prr->save()) {
                $history = new TransactionHistory;
                $history->transaction_no = $prr->reference_num;
                $history->action = 'Uploaded an attachment';
                $history->action_by = auth()->user()->id;
                $history->remarks = $request->remarks;
                $history->save();
            }

            Alert::success('Accomplished Report Successfully Attached.')->persistent('Dismiss');
            return redirect('/process-review');

        }catch(\Throwable $exception) {
            Log::error('Failed to update process review request.', [
                'user_id'   => auth()->id(),
                'exception' => $exception,
            ]);

            Alert::error('Unable to update the process review request. Please try again.')->persistent('Dismiss');
            return redirect()->back()->withInput();
        }
    }

    public function completed(Request $request, $id)
    {
         // dd($request);
        $request->validate([
            // 'report_draft_link'  => 'required|string',
            'remarks'       => 'required|string',
            'submission_action' => 'required|in:cancel,completed',
            // 'report_attachment' => 'required_if:submission_action,completed|file|mimes:pdf',
        ]);

        try{
            $prr = ProcessReviewRequest::findOrFail($id);
            $prr->remarks = $request->remarks;
            // $prr->report_draft_link = $request->report_draft_link;
            $prr->status = $request->submission_action === 'cancel' ? 'Cancelled' : 'Completed';
            // $prr->save();

             if($request->hasFile('report_attachment')) {
                $file = $request->file('report_attachment');
                $uploadPath = public_path('prr_attachments');

                if(!file_exists($uploadPath)) {
                    mkdir($uploadPath,0755, true);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move($uploadPath, $fileName);
                $prr->report_attachment = '/prr_attachments/' . $fileName;

            }

            if($prr->save()) {
                $history = new TransactionHistory;
                $history->transaction_no = $prr->reference_num;
                $history->action = $request->submission_action === 'cancel' ? 'Cancelled' : 'Tagged as completed';
                $history->action_by = auth()->user()->id;
                $history->remarks = $request->remarks;
                $history->save();
            }

            Alert::success($prr->status === 'Cancelled' ? 'Successfully Cancelled' : 'Successfully Tagged as Completed')->persistent("Dismiss");
            return redirect('/process-review');
        }catch(\Throwable $exception) {
            Log::error('Failed to accept process review request.', [
                'user_id'   => auth()->id(),
                'exception' => $exception,
            ]);
            Alert::error('Unable to accept the process review request. Please try again.')->persistent('Dismiss');
            return redirect()->back()->withInput();   
        }
       
    }

    public function remarks(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string|max:255'
        ]);

        try {
            $prr = ProcessReviewRequest::findOrFail($id);
            $prr->remarks = $request->remarks;

            if ($prr->save()) {
                $history = new TransactionHistory;
                $history->transaction_no = $prr->reference_num;
                $history->action = 'Remarks';
                $history->action_by = auth()->user()->id;
                $history->remarks = $request->remarks;
                $history->save();
            }

            Alert::success('Remarks successfully saved.')->persistent('Dismiss');
            return redirect('/process-review');
        } catch (\Throwable $exception) {
            Log::error('Failed to save process review remarks.', [
                'user_id' => auth()->id(),
                'process_review_request_id' => $id,
                'exception' => $exception,
            ]);

            Alert::error('Unable to save the remarks. Please try again.')->persistent('Dismiss');
            return redirect()->back()->withInput();
        }
    }
}
