<?php

namespace App\Http\Controllers;

use App\Department;
use App\SupportingDocument;
use App\SupportingDocumentsDepartment;
use App\SupportingDocumentsCompany;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SupportingDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supporting_documents = SupportingDocument::where('status', 'Approved')
                        ->orWhere('status', NULL)
                        ->get();
        if ((auth()->user()->role == "User" || auth()->user()->role == "Department Head") && auth()->user()->audit_role == null)
        {
            $supporting_documents = SupportingDocument::whereHas('supporting_document_dept', function($q) {
                    $q->where('department_id', auth()->user()->department_id);
                })
                ->get();
        }
        $departments = Department::whereNull('status')->get();

        return view('supporting_documents', compact('supporting_documents','departments'));
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
        $attachment = $request->file('attachment');

        $name = time().'_'.$attachment->getClientOriginalName();

        $attachment->move(public_path('supporting_documents'), $name);

        // Save Supporting Document
        $supporting_documents = new SupportingDocument;

        // $supporting_documents->department_id = auth()->user()->department_id;
        $supporting_documents->title = $request->title;
        $supporting_documents->uploaded_by = auth()->user()->id;
        $supporting_documents->file = '/supporting_documents/'.$name;
        $supporting_documents->supporting_docs = $request->supporting_documents;
        $supporting_documents->others = $request->others;
        $supporting_documents->status = "Pending";

        $supporting_documents->save();


        // Save selected departments
        foreach ((array) $request->input('department', []) as $department) {

            $supporting_documents_department = new SupportingDocumentsDepartment;

            $supporting_documents_department->department_id = $department;
            $supporting_documents_department->supporting_document_id = $supporting_documents->id;

            $supporting_documents_department->save();
        }


        // Save company of logged-in user
        $supporting_documents_company = new SupportingDocumentsCompany;

        $supporting_documents_company->company_id = auth()->user()->company_id;
        $supporting_documents_company->supporting_document_id = $supporting_documents->id;
        // $supporting_documents_company->final_status = 'Pending';

        $supporting_documents_company->save();


        Alert::success('Successfully Saved')->persistent('Dismiss');

        return back();
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
    public function destroy(Request $request)
    {
        $supporting_document = SupportingDocument::findOrFail($request->id);
        $supporting_document->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }


    public function forApprovalSupporting()
    {
        $query = SupportingDocument::query();


        $query->where(function($q) {
            $q->where('status', 'Pending')
            ->orWhere('status', 'Declined');
        });


        if (auth()->user()->role == "Document Control Officer") {
            $query->whereHas('supporting_document_company', function($q) {
                $q->where('company_id', auth()->user()->company_id);
            });
        }

        $supporting_documents = $query->get();

        return view('for-approval-supporting-documents', compact('supporting_documents'));
    }

    public function approvedSupporting(Request $request, $id)
    {
        $supportingDocuments = SupportingDocument::findOrFail($id);
        
        $supportingDocuments->status = $request->status;
        $supportingDocuments->remarks = $request->supporting_comment;
        
        if ($request->has('visibility')) {
            $supportingDocuments->visibility = $request->visibility;
        }

        $supportingDocuments->save();

        Alert::success('Successfully ' . $request->status)->persistent('Dismiss');
        return back();
    }
}
