<?php

namespace App\Console\Commands;

use App\Operations\Admin\AutoBill;
use App\Operations\Core\BillingEngine;
use App\Operations\Core\MetricsOperation;
use App\Operations\Core\NotificationEngine;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TaskDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Tasks that Run at 2am.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        BillingEngine::dailyAccountInvoiceCheck();
        AutoBill::run();
        BillingEngine::checkPastDueInvoices();
        NotificationEngine::run();
        Artisan::call('logic:backup');
        return 0;
    }
}
