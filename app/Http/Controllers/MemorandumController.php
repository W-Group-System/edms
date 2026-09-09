<?php

namespace App\Http\Controllers;

use App\Document;
use App\Memorandum;
use App\MemorandumDocument;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MemorandumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     // $documents = Document::where('department_id', auth()->user()->department_id)->where('category', 'POLICY')->get();
    //     $documents = Document::whereIn('category', ['POLICY', 'PROCEDURE'])->get();
    //     $memos = Memorandum::get();
    //     $memos = Memorandum::where('final_status', NULL)
    //                     ->orWhere('final_status', 'Approved')
    //                     ->get();
                
    //     //            ->get();

    //     // Private memos are not visible to any users
    //     if((auth()->user()->role == 'User' && auth()->user()->audit_role == null) || (auth()->user()->role == 'Department Head' && auth()->user()->audit_role == null))
    //     {
    //         $memos = Memorandum::where('department_id', auth()->user()->department_id)->orWhere('status', 'Public')->get();
    //     }
        
    //     return view('memorandum', compact('documents', 'memos'));
    //     // dd($memos);
    // }
    public function index()
    {
        $documents = Document::whereIn('category', ['POLICY', 'PROCEDURE'])->get();
        $query = Memorandum::query();

        
        $query->where(function($q) {
            $q->whereIn('final_status', ['Approved'])
              ->orWhereNull('final_status')
              ->orWhere(function($sub) {
                  $sub->where('final_status', 'Declined')
                        ->where('uploaded_by', auth()->id());
              });
        });

        if ((auth()->user()->role == 'User' && auth()->user()->audit_role == null) || (auth()->user()->role == 'Department Head' && auth()->user()->audit_role == null)) {
            $query->where(function($q) {
                $q->where('department_id', auth()->user()->department_id)
                  ->orWhere('status', 'Public');
            });
        }

        $memos = $query->get();

        return view('memorandum', compact('documents', 'memos'));
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
        // dd($request->all());
        $memo = new Memorandum();
        $memo->department_id = auth()->user()->department_id;
        $memo->company_id = auth()->user()->company_id;
        $memo->memo_number = $request->memo_number;
        $memo->title = $request->title;
        $memo->released_date = $request->released_date;
        // $memo->document_id = $request->document;
        $memo->type = $request->type;
        $memo->uploaded_by = auth()->user()->id;
        
        $memo_file = $request->file('memo_file');
        $name = time().'-'.$memo_file->getClientOriginalName();
        $memo_file->move(public_path('memorandum_files'), $name);
        $file = '/memorandum_files/'.$name;

        $memo->file_memo = $file;
        // $memo->status = 'Private';
        // $memo->status = 'For approval';
        $memo->final_status = 'Pending';
        $memo->save();

        if($request->has('document'))
        {
            foreach($request->document as $document)
            {
                $memo_docs = new MemorandumDocument();
                $memo_docs->memorandum_id = $memo->id;
                $memo_docs->document_id = $document;
                $memo_docs->save();
            }
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
        // dd($request->all(),$id);
        $memo = Memorandum::findOrFail($id);
        // $memo->department_id = auth()->user()->department_id;
        // $memo->memo_number = $request->memo_number;
        $memo->title = $request->title;
        $memo->released_date = $request->released_date;
        $memo->document_id = $request->document;
        $memo->type = $request->type;
        $memo->uploaded_by = auth()->user()->id;
        
        if ($request->has('memo_file'))
        {
            $memo_file = $request->file('memo_file');
            $name = time().'-'.$memo_file->getClientOriginalName();
            $memo_file->move(public_path('memorandum_files'), $name);
            $file = '/memorandum_files/'.$name;
    
            $memo->file_memo = $file;
        }
        $memo->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $memo = Memorandum::findOrFail($request->id);
        $memo->delete();

        $memo_docs = MemorandumDocument::where('memorandum_id', $request->id)->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }

    public function updateStatus(Request $request, $id)
    {
        $memo = Memorandum::findOrFail($id);
        if($request->has('status'))
        {
            $memo->status = 'Public';
            Alert::success('Successfully Public')->persistent('Dismiss');
        }
        else
        {
            $memo->status = 'Private';
            Alert::success('Successfully Private')->persistent('Dismiss');
        }
        $memo->save();

        return back();
    }

    public function approveMemorandum(Request $request, $id)
    {
        $memo = Memorandum::findOrFail($id);
        
        $memo->status = ($request->status === 'Approved') ? $request->visibility : 'Private'; 
        $memo->final_status = $request->status;
        $memo->approved_by = auth()->user()->name;
        $memo->remarks = $request->memo_comment;

        $memo->save();

        // FIXED: Space added between 'Successfully ' and $request->status
        Alert::success('Successfully ' . $request->status)->persistent('Dismiss');
        return back();
    }

    public function forApproval()
    {
        $documents = Document::whereIn('category', ['POLICY', 'PROCEDURE'])->get();

        $query = Memorandum::query();

        $query->where(function($q) {
            $q->where('final_status', 'Pending')
            ->orWhere('final_status', 'Declined');
           
        });

        if (auth()->user()->role == 'Document Control Officer') {
            $query->where('company_id', auth()->user()->company_id);
        }

        $memos = $query->get();

        return view('for-approval-memorandum', compact('memos', 'documents'));
    }
}
