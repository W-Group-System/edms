<?php

namespace App\Http\Controllers;

use App\Permit;
use App\Department;
use App\Document;
use App\ChangeRequest;
use App\DocumentType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $change_requests = ChangeRequest::get();
        $documents = Document::get();
        $departments = Department::with('documents','obsoletes')->withCount('documents','obsoletes')->get();
        $categories = DocumentType::get();
        // $departments = Department::with('documents')->whereHas('documents')->get();
        $permits = Permit::with('company', 'department')->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->get();
        return view('home',
        array(
            'permits' =>  $permits,
            'departments' =>  $departments,
            'change_requests' =>  $change_requests,
            'documents' =>  $documents,
            'categories' =>  $categories,

        ));
    }
    public function search(Request $request)
    {
        $documents = [];
        $request_documents = [];
        if($request->search)
        {
            $documents = Document::where('control_code','like','%' . $request->search. '%')->orWhere('title','like','%' . $request->search. '%')->get();
        }
       
        $departments = Department::with('documents','obsoletes')->whereHas('documents')->orWhereHas('obsoletes')->get();
        return view('search',
        array(
            'documents' => $documents,
            'search' => $request->search,
            'request_documents' => $request_documents,
        ));
    }
}
