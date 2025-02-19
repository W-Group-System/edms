<?php

namespace App\Http\Controllers;

use App\Document;
use App\Memorandum;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MemorandumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::where('department_id', auth()->user()->department_id)->where('category', 'POLICY')->get();
        $memos = Memorandum::where('department_id', auth()->user()->department_id)->get();
        
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
        $memo->memo_number = $request->memo_number;
        $memo->title = $request->title;
        $memo->released_date = $request->released_date;
        $memo->document_id = $request->document;
        $memo->uploaded_by = auth()->user()->id;
        
        $memo_file = $request->file('memo_file');
        $name = time().'-'.$memo_file->getClientOriginalName();
        $memo_file->move(public_path('memorandum_files'), $name);
        $file = '/memorandum_files/'.$name;

        $memo->file_memo = $file;
        $memo->save();

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
    public function destroy($id)
    {
        //
    }
}
