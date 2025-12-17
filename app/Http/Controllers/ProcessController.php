<?php

namespace App\Http\Controllers;

use App\Company;
use App\Department;
use App\Process;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class ProcessController extends Controller
{
    public function index(Request $request)
    {
        $processes = Process::orderBy('department_id')->get();
        $departments = Department::get();
        $companies = Company::whereIn('id', [1, 2, 3])->get();

        return view('process.index',
        array(
            'processes' => $processes,
            'departments' => $departments,
            'companies' => $companies,
        ));
    }

    public function new_process(Request $request)
    {
        $process = new Process;
        $process->process_name = $request->process_name;
        $process->department_id = $request->department_id;
        $process->company_id = $request->company_id;
        $process->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }

    public function edit(Request $request, $id)
    {
        $process = Process::findOrFail($id);
        $process->process_name = $request->process_name;
        $process->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }

    public function deactivate(Request $request)
    {
        $process = Process::where('id', $request->id)->first();
        $process->status = "deactivated";
        $process->save();

        return "success";
    }
    public function activate(Request $request)
    {
        $process = Process::where('id', $request->id)->first();
        $process->status = null;
        $process->save();

        return "success";
    }
}
