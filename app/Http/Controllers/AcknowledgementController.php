<?php

namespace App\Http\Controllers;
use App\ChangeRequest;
use App\Acknowledgement;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class AcknowledgementController extends Controller
{
    //
    public function index()
    {
        $requests = ChangeRequest::orderBy('id','desc')->where('request_type','!=','Obsolete')->where('status','Approved')->get();
        if(auth()->user()->role == "User")
        {
            $requests = ChangeRequest::where('user_id',auth()->user()->id)->orderBy('id','desc')->where('request_type','!=','Obsolete')->where('status','Approved')->get();
            if (auth()->user()->department_id == 8)
            {
                $requests = ChangeRequest::orderBy('id','desc')->where('request_type','!=','Obsolete')->where('status','Approved')->get();
            }
        }
        else if(auth()->user()->role == "Document Control Officer")
        {
            $requests = ChangeRequest::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('status','Approved')->where('request_type','!=','Obsolete')->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Department Head")
        {
            $requests = ChangeRequest::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->where('status','Approved')->where('request_type','!=','Obsolete')->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Documents and Records Controller")
        {
            $requests = ChangeRequest::where('user_id',auth()->user()->id)->orderBy('id','desc')->where('request_type','!=','Obsolete')->where('status','Approved')->get();
        }
        return view('acknowledgements',
        
        array(
            'requests' =>  $requests,
        ));
    }
    public function uploaded()
    {
        $requests = ChangeRequest::orderBy('id','desc')->where('request_type','!=','Obsolete')->where('status','Approved')->get();
        if(auth()->user()->role == "User")
        {
            $requests = ChangeRequest::where('user_id',auth()->user()->id)->orderBy('id','desc')->where('request_type','!=','Obsolete')->where('status','Approved')->get();
        }
        else if(auth()->user()->role == "Document Control Officer")
        {
            $requests = ChangeRequest::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('status','Approved')->where('request_type','!=','Obsolete')->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Department Head")
        {
            $requests = ChangeRequest::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->where('status','Approved')->where('request_type','!=','Obsolete')->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Documents and Records Controller")
        {
            $requests = ChangeRequest::where('user_id',auth()->user()->id)->orderBy('id','desc')->where('request_type','!=','Obsolete')->where('status','Approved')->get();
        }
        return view('uploaded-acknowledgement',
        
        array(
            'requests' =>  $requests,
        ));
    }
    public function store(Request $request, $id)
    {
        $attachment = $request->file('file');
        $name = time() . '_' . $attachment->getClientOriginalName();
        $attachment->move(public_path() . '/acknowledgement-attachment/', $name);
        $file_name = '/acknowledgement-attachment/' . $name;
        $upload = new Acknowledgement;
        $upload->change_request_id = $id;
        $upload->file = $file_name;
        $upload->user_id = auth()->user()->id;
        $upload->save();

        Alert::success('Successfully Uploaded')->persistent('Dismiss');
        return back();

    }

    public function aknowledge(Request $request)
    {
        $upload = new Acknowledgement;
        $upload->change_request_id = $request->id;
        $upload->user_id = auth()->user()->id;
        $upload->save();
        Alert::success('Successfully Acknowledged')->persistent('Dismiss');
        return back();
    }

    public function autoAcknowledgeRequests()
    {
        $requests = ChangeRequest::where('status', 'Approved')
            ->where('updated_at', '<=', Carbon::now()->subDays(3))
            ->get();

        foreach ($requests as $request) {
            $auto = new Acknowledgement;
            $auto->change_request_id = $request->id;
            $auto->user_id = $request->user_id;
            $auto->save();
        }
    }
    public function editUpload(Request $request)
    {
        $acknowledgement = Acknowledgement::where('change_request_id', $request->change_request_id)->first();
        
        if ($request->has('acknowledgement_file'))
        {
            $attachment = $request->file('acknowledgement_file');
            $name = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path() . '/acknowledgement-attachment/', $name);

            $file_name = '/acknowledgement-attachment/' . $name;

            $acknowledgement->file = $file_name;
            $acknowledgement->save();
        }

        Alert::success('Successfully Uploaded')->persistent('Dismiss');
        return back();
    }
}
