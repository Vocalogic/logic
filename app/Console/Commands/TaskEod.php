<?php

namespace App\Console\Commands;

use App\Operations\Core\MetricsOperation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TaskEod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:eod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'End of Day Tasks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        MetricsOperation::run();            // Gather Metrics
        Artisan::call('generate:sitemap');
        return 0;
    }
}
