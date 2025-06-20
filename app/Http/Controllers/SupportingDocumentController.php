<?php

namespace App\Http\Controllers;

use App\Department;
use App\SupportingDocument;
use App\SupportingDocumentsDepartment;
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
        $supporting_documents = SupportingDocument::get();
        if (auth()->user()->role == "User" || auth()->user()->role == "Department Head")
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
        $attachment->move(public_path('supporting_documents'),$name);

        $supporting_documents = new SupportingDocument;
        // $supporting_documents->department_id = auth()->user()->department_id;
        $supporting_documents->title = $request->title;
        $supporting_documents->uploaded_by = auth()->user()->id;
        $supporting_documents->file = '/supporting_documents/'.$name;
        $supporting_documents->supporting_docs = $request->supporting_documents;
        $supporting_documents->others = $request->others;
        $supporting_documents->save();

        foreach($request->department as $department)
        {
            $supporting_documents_department = new SupportingDocumentsDepartment;
            $supporting_documents_department->department_id = $department;
            $supporting_documents_department->supporting_document_id = $supporting_documents->id;
            $supporting_documents_department->save();
        }
        
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
}
