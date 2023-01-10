<?php

namespace App\Console\Commands;

use App\Operations\Core\BillingEngine;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TaskHourly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tasks to be Completed every hour';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        BillingEngine::syncFees();
        Artisan::call('recordings:update');
        Artisan::call('recordings:sync');
        return 0;
    }
}
