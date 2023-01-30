<?php

namespace App\Console\Commands;

use App\Enums\Core\IntegrationType;
use App\Operations\API\Control;
use App\Operations\Integrations\Backup\Backup;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class LogicBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logic:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take a backup and Submit to Integration';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle()
    {
        // If we have no enabled integration then just exit.
        if (!hasIntegration(IntegrationType::Backup))
        {
            $this->warn("No integration has been selected. Go to admin -> integrations -> backups to configure.");
            return Command::SUCCESS;
        }
        $integrationName = getIntegration(IntegrationType::Backup)->connect()->getName();
        $this->alert("Using $integrationName for Backups");
        $this->info("Compressing and Submitting Site Data...");
        Backup::backupSiteData();
        $this->info("Compressing and Submitting Database Export...");
        Backup::backupDatabase();
        $this->info("Finished!");
        return Command::SUCCESS;
    }
}
