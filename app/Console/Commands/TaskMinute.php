<?php

namespace App\Console\Commands;

use App\Enums\Core\CommKey;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class TaskMinute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:minute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tasks to be completed every minute';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (cache(CommKey::GlobalUpgradeTrigger->value))
        {
            Artisan::call('logic:upgrade');
            Cache::forget(CommKey::GlobalUpgradeTrigger->value);
        }
        return 0;
    }
}
