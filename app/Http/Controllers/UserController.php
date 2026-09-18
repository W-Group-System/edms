<?php

namespace App\Http\Controllers;

use App\AccountRequest;
use App\User;
use App\Company;
use App\Department;
use App\UserDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::with('department', 'company')->get();
        $companies = Company::get();
        $departments = Department::get();
        $roles = $this->roles();
        return view('users', array(
            'users' => $users,
            'companies' => $companies,
            'departments' => $departments,
            'roles' => $roles,
        ));
    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:50',
            'email' => 'email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);


        $new_account = new User;
        $new_account->name = $request->name;
        $new_account->email = $request->email;
        $new_account->company_id = $request->company;
        $new_account->department_id = $request->department;
        $new_account->role = $request->role;
        $new_account->password = bcrypt($request->password);
        $new_account->save();
        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }
    public function changepassword(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $user = User::where('id', $id)->first();
        $user->password = bcrypt($request->password);
        $user->save();
        Alert::success('Successfully Change Password')->persistent('Dismiss');
        return back();
    }
    public function deactivate_user(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->status = 1;
        $user->password = "";
        $user->save();

        return "success";
    }
    public function activate_user(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->status = null;
        $user->save();

        return "success";
    }
    public function edit_user(Request $request, $id)
    {

        $this->validate($request, [
            'email' => 'unique:users,email,' . $id,
        ]);

        $account = User::where('id', $id)->first();
        $account->name = $request->name;
        $account->email = $request->email;
        $account->company_id = $request->company;
        $account->department_id = $request->department;
        $account->role = $request->role;
        $account->save();

        $share_department = UserDepartment::where('user_id',$id)->delete();
        if($request->share_department)
        {
            foreach($request->share_department as $d)
            {
                $department = new UserDepartment;
                $department->user_id = $id;
                $department->department_id = $d;
                $department->created_by = auth()->user()->id;
                $department->save();
            }
        }
      

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    public function roles()
    {
        $roles = [
            'User' => 'User',
            // 'Documents and Records Controller' => 'Documents and Records Controller',
            'Department Head' => 'Department Head',
            'Document Control Officer' => 'Document Control Officer',
            'Business Process Manager' => 'Business Process Manager',
            'Management Representative' => 'Management Representative',
            'Administrator' => 'Administrator',
        ];

        return $roles;
    }

    public function addUserFromWpro(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        
        if ($user == null)
        {
            $users = new User;
            $users->name = $request->name;
            $users->email = $request->email;
            $users->password = $request->password;
            $users->department_id = $request->department_id;
            $users->company_id = $request->company_id;
            $users->role = $request->role;
            $users->save();

            return response()->json(['message' => 'Successfully Saved']);
        }
        else
        {
            return response()->json(['message' => 'Error! The email is existing in our system']);
        }
        
    }

    public function request_aacount() {

        // if((auth()->user()->role == "User") || (auth()->user()->role == "Department Head"))
        // {
            $account_requests = AccountRequest::where('department_id', auth()->user()->department_id)
                ->where('request_by', auth()->user()->id)->get();
        // } 
        // elseif ((auth()->user()->role == "Business Process Manager") || auth()->user()->role == "Administrator") {
        //     $account_requests = AccountRequest::where('status',"!=", "Cancelled")->get();
        // }
        $roles = $this->roles();
        return view('account_request.account_requests', array(
            'account_requests' => $account_requests,
        ));
    }

    public function store_request_account(Request $request)
    {
        $new_account = new AccountRequest;
        $new_account->name = $request->name;
        $new_account->email = $request->email;
        $new_account->company_id = $request->company;
        $new_account->department_id = $request->department;
        $new_account->reason = $request->reason;
        $new_account->position = $request->position;
        $new_account->request_by = auth()->user()->id;
        $new_account->status = "Pending";
        $new_account->save();
        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }

    public function cancel(Request $request)
    {
        $cancelRequest = AccountRequest::findOrFail($request->id);
        $cancelRequest->status = "Cancelled";
        $cancelRequest->save();

        Alert::success('Successfully Cancelled')->persistent('Dismiss');
        return back();
    }

    public function forApproval() {

        if ((auth()->user()->role == "Business Process Manager") || auth()->user()->role == "Administrator") {
            $account_requests = AccountRequest::where('status',"!=", "Cancelled")->get();
        } else {
            return response("You don't have permission to access this", 403);
        }
        return view('account_request.account_request_approval', array(
            'account_requests' => $account_requests,
        ));
    }
    public function approve_request_account(Request $request, $id)
    {
        $approveAccount = AccountRequest::findOrFail($id);
        $approveAccount->status = $request->status;
        $approveAccount->approver_remarks = $request->approver_remarks;
        $approveAccount->save();

        if ($request->status == "Approved") {
            $new_account = new User;
            $new_account->name = $request->name;
            $new_account->email = $request->email;
            $new_account->company_id = $request->company;
            $new_account->department_id = $request->department;
            $new_account->status = "New Account";
            $new_account->account_request_id = $id;
            $new_account->password = Hash::make(Str::random(10));
            $new_account->save();
        }
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function edit_new_account(Request $request, $id)
    {

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);

        $account = User::where('id', $id)->first();
        $account->name = $request->name;
        $account->email = $request->email;
        $account->company_id = $request->company;
        $account->department_id = $request->department;
        $account->role = $request->role;
        $account->status = "";
        $account->password = bcrypt($request->password);
        $account->save();

        $share_department = UserDepartment::where('user_id',$id)->delete();
        if($request->share_department)
        {
            foreach($request->share_department as $d)
            {
                $department = new UserDepartment;
                $department->user_id = $id;
                $department->department_id = $d;
                $department->created_by = auth()->user()->id;
                $department->save();
            }
        }
      

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    
}
