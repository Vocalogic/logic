<?php

namespace App\Console\Commands;

use App\Enums\Core\CommKey;
use App\Exceptions\LogicException;
use App\Operations\API\Control;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Process;

class UpgradeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logic:upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade to the Latest Version';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws LogicException
     * @throws GuzzleException
     */
    public function handle()
    {
        $commands = [
            'php artisan down',
            'git stash',
            'rm -rf public/js/app.js',
            'rm -rf public/js/cart.js',
            'rm -rf public/js/logic.js',
            'rm -rf public/css/logic.css',
            'git pull origin master',
            'chmod 777 storage -R',
            'composer install',
            'composer update',
            'php artisan config:cache',
            'php artisan config:clear',
            'php artisan migrate --force',
            'php artisan db:seed --force',
            'npm install',
            'npm run prod',
            'php artisan up',
        ];
        foreach ($commands as $command)
        {
            $process = Process::fromShellCommandline($command);
            $this->info("Executing: $command");
            $process->run();
            if (!$process->isSuccessful())
            {
                $this->warn("Error Executing.." . $process->getErrorOutput());
                info("Error Executing: $command - " . $process->getErrorOutput());
            }
            $this->info($command . " - " . $process->getOutput());
            info("Ran $command - " . $process->getOutput());
        }
        $this->alert('You are running Logic v' . currentVersion()->version);
        return 0;
    }
}
