<?php

namespace App\Http\Controllers;

use App\Permit;
use App\Department;
use App\Document;
use App\ChangeRequest;
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
        // $departments = Department::with('documents')->whereHas('documents')->get();
        $permits = Permit::with('company', 'department')->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->get();
        return view('home',
        array(
            'permits' =>  $permits,
            'departments' =>  $departments,
            'change_requests' =>  $change_requests,
            'documents' =>  $documents,

        ));
    }
    public function search()
    {
        $documents = Document::where('public',1)->get();
        $departments = Department::with('documents','obsoletes')->whereHas('documents')->orWhereHas('obsoletes')->get();
        return view('search',
        array(

        ));
    }
}
