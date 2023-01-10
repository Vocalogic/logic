<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class e2eCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:e2e';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Staging and Local End to End Testing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (env('APP_ENV') != 'local')
            $this->error("This cannot be run in production modes. Local ENV only.");

        // Kill the entire database and run migrations.
        $this->info("Destroying and Recreating Database..");
        DB::statement('DROP DATABASE logic');
        DB::statement('CREATE DATABASE logic');
        DB::statement('USE logic');
        $this->info("Executing Migrations..");
        Artisan::call('migrate');
        $this->info("Seeding Database...");
        Artisan::call('db:seed');
        $this->info("Executing Dusk Tests");
        Artisan::call('dusk');


        return Command::SUCCESS;
    }
}
