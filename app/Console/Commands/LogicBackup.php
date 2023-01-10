<?php

namespace App\Console\Commands;

use App\Operations\API\Control;
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
    protected $description = 'Take a backup and Send it to Control';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle()
    {
        // Check if there is a license key - if not exit.
        if (!setting('brand.license')) return Command::SUCCESS;

        $this->info("Starting Backup Routine...");
        $this->info("Working Path: " . getenv('PATH'));
        $this->info("Compressing Site Data..");
        $license = setting('brand.license');
        $date = now()->format("Y-m-d");
        $file = "Backup-$license-$date.tar.gz";
        $command = new Process([
            "/usr/bin/tar",
            "-cvzf",
            $file,
            "storage"
        ]);
        $command->run();
        if (!$command->isSuccessful())
        {
            $this->error($command->getErrorOutput());
        }
        $this->info("Exporting Database ...");
        $user = trim(env('DB_USERNAME'));
        $password = trim(env('DB_PASSWORD'));
        $db = trim(env('DB_DATABASE'));
        $command = Process::fromShellCommandline("/usr/bin/mysqldump -u $user -p{$password} $db > db-$date.sql");
        $command->run();
        $this->info("Compressing Database..");
        $command = Process::fromShellCommandline("gzip db-$date.sql");
        $command->run();
        if (!$command->isSuccessful())
        {
            $this->error($command->getErrorOutput());
        }
        $command->run();
        // Should have a file db-$date.sql.gz
        $this->info("Uploading to Control..");
        $c = new Control();
        $site = base64_encode(file_get_contents($file));
        $database = base64_encode(file_get_contents("db-$date.sql.gz"));
        try
        {
            $c->submitBackup($site, $database);
        } catch (Exception $e)
        {
            $this->error($e->getMessage());
        }
        unlink($file);
        unlink("db-$date.sql.gz");
        return Command::SUCCESS;
    }
}
