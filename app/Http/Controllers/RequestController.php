<?php

namespace App\Http\Controllers;
use App\Document;
use App\CopyRequest;
use App\CopyApprover;
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
        $documents = Document::get();
        $requests = CopyRequest::get();
        return view('requests',
        array(
            'documents' =>  $documents,
            'requests' =>  $requests,
        ));
    }
    public function forReview()
    {
        //
        return view('for_reviews',
        array(
           
        ));
    }
    public function forApproval()
    {
        //
        $copy_for_approvals = CopyApprover::where('user_id',auth()->user()->id)->get();

        return view('for_approval',
        array(
           'copy_for_approvals' => $copy_for_approvals,
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
}
