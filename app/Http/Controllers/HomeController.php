<?php

namespace App\Http\Controllers;

use App\Permit;
use App\Department;
use App\Document;
use App\ChangeRequest;
use App\CopyRequest;
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
        $copy_requests = CopyRequest::get();
        $documents = Document::where('status',null)->get();
        $departments = Department::with('documents','obsoletes')->withCount('documents','obsoletes')->get();
        $permits = Permit::with('company', 'department')->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->get();
        if((auth()->user()->role != "Administrator") || (auth()->user()->role != "Management Representative") || (auth()->user()->role != "Business Process Manager"))
        {
            if((auth()->user()->role == "Department Head"))
            {
                $departments = Department::whereIn('id',(auth()->user()->department_head)->pluck('id')->toArray())->with('documents','obsoletes')->withCount('documents','obsoletes')->get();
                $change_requests = ChangeRequest::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->get();
                $copy_requests = CopyRequest::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->get();
                $documents = Document::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->where('status',null)->toArray())->get();
            }
            elseif((auth()->user()->role == "Documents and Records Controller"))
            {
                $departments = Department::where('id',auth()->user()->department_id)->with('documents','obsoletes')->withCount('documents','obsoletes')->get();
                $change_requests = ChangeRequest::where('user_id',auth()->user()->id)->get();
                $copy_requests = CopyRequest::where('user_id',auth()->user()->id)->get();
                $documents = Document::where('department_id',auth()->user()->department_id)->where('status',null)->get();

            }
            elseif((auth()->user()->role == "Document Control Officer"))
            {
                $departments = Department::whereIn('id',(auth()->user()->dco))->with('documents','obsoletes')->withCount('documents','obsoletes')->get();
                $change_requests = ChangeRequest::whereIn('department_id',(auth()->user()->dco))->get();
                $copy_requests = CopyRequest::whereIn('department_id',(auth()->user()->dco))->get();
                $documents = Document::whereIn('department_id',(auth()->user()->dco))->where('status',null)->get();

            }

        }

        $categories = DocumentType::get();
        return view('home',
        array(
            'permits' =>  $permits,
            'departments' =>  $departments,
            'change_requests' =>  $change_requests,
            'documents' =>  $documents,
            'categories' =>  $categories,
            'copy_requests' =>  $copy_requests,

        ));
    }
    public function search(Request $request)
    {
        $documents = [];
        $request_documents = Document::where('public','!=',null)->where('status',null)->get();
        if($request->search)
        {
            $documents = Document::where('control_code','like','%' . $request->search. '%')->orWhere('title','like','%' . $request->search. '%')->where('status',null)->get();
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
