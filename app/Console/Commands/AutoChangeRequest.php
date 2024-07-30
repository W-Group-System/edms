<?php

namespace App\Console\Commands;

use App\ChangeRequest;
use App\DepartmentApprover;
use App\PreAssessment;
use App\RequestApprover;
use Illuminate\Console\Command;

class AutoChangeRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:autocr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatic Change Request';

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
        $preAssessment = PreAssessment::where('status', 'Pending')->get();

        foreach($preAssessment as $pa)
        {
            $target_date = date('Y-m-d', strtotime("+5 weekdays", strtotime($pa->created_at)));
            $date_today = date('Y-m-d');

            if ($target_date <= $date_today)
            {
                $pre_assessment = PreAssessment::where('id', $pa->id)->first();
                $pre_assessment->status = "Approved";
                $pre_assessment->save();
                
                $changeRequest = new ChangeRequest;
                $changeRequest->request_type = $pa->request_type;
                $changeRequest->effective_date = $pa->effective_date;
                $changeRequest->department_id = $pa->department_id;
                $changeRequest->user_id = $pa->user_id;
                $changeRequest->type_of_document = $pa->type_of_document;
                $changeRequest->document_id = $pa->document_id;
                $changeRequest->change_request = $pa->change_request;
                $changeRequest->reason_for_changes = $pa->reason_for_changes;
                $changeRequest->link_draft = $pa->link_draft;
                $changeRequest->status = $pa->status;
                $changeRequest->level = $pa->level;
                $changeRequest->company_id = $pa->company_id;
                $changeRequest->control_code = $pa->control_code;
                $changeRequest->title = $pa->title;
                $changeRequest->revision = $pa->revision;
                $changeRequest->original_attachment_pdf = $pa->original_attachment_pdf;
                $changeRequest->original_attachment_soft_copy = $pa->original_attachment_soft_copy;
                $changeRequest->pdf_copy = $pa->pdf_copy;
                $changeRequest->soft_copy = $pa->soft_copy;
                $changeRequest->fillable_copy = $pa->fillable_copy;
                $changeRequest->supporting_documents = $pa->supporting_documents;
                $changeRequest->save();

                $departmentApprovers = DepartmentApprover::where('department_id', $pa->department_id)->orderBy('level', 'asc')->get();
                foreach($departmentApprovers as $approver)
                {
                    $requestApprover = new RequestApprover;
                    $requestApprover->change_request_id = $changeRequest->id;
                    $requestApprover->user_id = $approver->user_id;
                    if($approver->level == 1)
                    {
                        $requestApprover->status = "Pending";
                        $requestApprover->start_date = date('Y-m-d');
                    }
                    else
                    {
                        $requestApprover->status = "Waiting";
                    }
                    $requestApprover->level = $approver->level;
                    $requestApprover->save();
                }
            }

        }
    }
}
