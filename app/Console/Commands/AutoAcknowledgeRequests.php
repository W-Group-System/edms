<?php

namespace App\Console\Commands;

use App\Acknowledgement;
use App\ChangeRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoAcknowledgeRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:auto-acknowledge';

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
        $requests = ChangeRequest::where('status', 'Approved')
            ->where('updated_at', '<=', Carbon::now()->subDays(3))
            ->get();

        foreach ($requests as $request) {

            $exists = Acknowledgement::where('change_request_id', $request->id)
                ->where('user_id', $request->user_id)
                ->exists();

            if ($exists) {
                continue;
            }

            $auto = new Acknowledgement;
            $auto->change_request_id = $request->id;
            $auto->user_id = $request->user_id;
            $auto->save();
        }

        $this->info('Auto Acknowledge completed');
    }
}
