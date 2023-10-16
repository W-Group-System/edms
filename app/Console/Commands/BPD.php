<?php

namespace App\Console\Commands;
use App\User;
use App\Notifications\PendingRequest;
use Illuminate\Console\Command;
use App\ChangeRequest;
class BPD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dco';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        info("START DCO");
        $users = User::where('status',null)->where('role','Document Control Officer')->get();
        foreach($users as $user)
        {
            $change_requests = ChangeRequest::with('approvers')->whereIn('department_id',($user->dco)->pluck('department_id')->toArray())->where('status','Pending')->get();

            $table = "<table style='margin-bottom:10px;' width='100%' border='1' cellspacing=0><tr><th>Code</th><th>Approver</th></tr>";
            foreach($change_requests as $request)
            {
                $approver = ($request->approvers)->where('level',$request->level)->first();
                $table .= "<tr><td>CR-".str_pad($request->id, 5, '0', STR_PAD_LEFT)."</td><td>".$approver->user->name."</td></tr>";
            }
            $table .= "</table>";
            if(count($change_requests) >0)
            {
                $user->notify(new PendingRequest($table));
            }
           
        }
        $users_d = User::where('status',null)->where('role','Business Process Manager')->orWhere('role','Management Representative')->get();
        foreach($users_d as $user)
        {
            $change_requests = ChangeRequest::with('approvers')->where('status','Pending')->get();

            $table = "<table style='margin-bottom:10px;' width='100%' border='1' cellspacing=0><tr><th>Code</th><th>Approver</th></tr>";
            foreach($change_requests as $request)
            {
                $approver = ($request->approvers)->where('level',$request->level)->first();
                $table .= "<tr><td>CR-".str_pad($request->id, 5, '0', STR_PAD_LEFT)."</td><td>".$approver->user->name."</td></tr>";
            }
            $table .= "</table>";
            if(count($change_requests) >0)
            {
                $user->notify(new PendingRequest($table));
            }
           
        }
      
        info("END DCO");
    }
}
