<?php

namespace App\Console\Commands;

use App\Models\FoodRequest;
use Illuminate\Console\Command;

class DeleteUnattendedRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unattended:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the requests which are unattended. this tasks is to run daily without fail';

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
     * @return int
     */
    public function handle()
    {
        // write the code here man
        $requests = FoodRequest::where('status','not approved');
    }
}
