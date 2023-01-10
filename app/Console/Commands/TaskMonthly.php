<?php

namespace App\Console\Commands;

use App\Models\Partner;
use Illuminate\Console\Command;

class TaskMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tasks to be performeed monthly';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (Partner::where('active', true)->where('status', 'Accepted')->get() as $partner)
        {
            info("[$partner->name] Checking for Outstanding Due Commissions..");
            try
            {
                $partner->checkCommissions();
            } catch (\Exception $e)
            {
                info("Failed to get commissions for partner during monthly check: " . $e->getMessage());
            }
        }
        return Command::SUCCESS;
    }
}
