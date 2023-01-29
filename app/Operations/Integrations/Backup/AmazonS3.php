<?php

namespace App\Operations\Integrations\Backup;

use App\Enums\Core\IntegrationRegistry;
use App\Operations\Integrations\BaseIntegration;
use App\Operations\Integrations\Integration;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class AmazonS3 extends BaseIntegration implements Integration
{
    public IntegrationRegistry $ident = IntegrationRegistry::AmazonS3;


    /**
     * Amazon S3 Name
     * @return string
     */
    public function getName(): string
    {
        return "Amazon S3";
    }

    /**
     * S3 Website
     * @return string
     */
    public function getWebsite(): string
    {
        return "https://aws.amazon.com/s3";
    }

    /**
     * S3 Description
     * @return string
     */
    public function getDescription(): string
    {
        return "Amazon Simple Storage Service (Amazon S3) is an object storage service offering industry-leading scalability, data availability, security, and performance.";
    }

    /**
     * S3 Logo
     * @return string
     */
    public function getLogo(): string
    {
        return "/assets/images/integrations/s3.png";
    }

    /**
     * Configure Amazon S3 Settings
     * @return object[]
     */
    public function getRequired(): array
    {
        return [
            (object)[
                'var'         => 's3_key',
                'item'        => "Amazon S3 Access Key:",
                'description' => "Enter the S3 Access Key for Bucket",
                'default'     => '',
                'protected'   => false,
            ],
            (object)[
                'var'         => 's3_secret',
                'item'        => "Amazon S3 Secret Key:",
                'description' => "Enter the S3 Secret Key for Bucket",
                'default'     => '',
                'protected'   => true,
            ],
            (object)[
                'var'         => 's3_region',
                'item'        => "Amazon S3 Region:",
                'description' => "Enter the S3 Region (i.e. us-east-1)",
                'default'     => '',
                'protected'   => false,
            ],
            (object)[
                'var'         => 's3_bucket',
                'item'        => "Amazon S3 Bucket:",
                'description' => "Enter the S3 Bucket Name for Saving Backups",
                'default'     => '',
                'protected'   => false,
            ]
        ];
    }

    /**
     * Use the Amazon S3 Driver in Laravel for Processing
     * @return void
     */
    public function backupSiteData(): void
    {
        if (!isset($this->config->s3_key) || !$this->config->s3_key) return; // Enabled but not configured.
        $date = now()->format("Y-m-d");
        $file = "Backup-Logic-$date.tar.gz";
        $command = Process::fromShellCommandline("/usr/bin/tar --exclude=\"$file\" -cvzf storage/$file *");
        info("Backing up all Site Data..");
        $command->run();
        if (!$command->isSuccessful())
        {
            error_log($command->getErrorOutput());
        }
        // Build Storage Driver
        $s3 = $this->setS3();
        $location = sprintf("%s/%s/", "backups", now()->format("M-Y"));
        try
        {
            info("Attempting to Store Backup to S3..");
            $s3->put($location . $file, file_get_contents(storage_path() . "/" . $file));
        } catch (Exception $e)
        {
            error_log("Failed to Upload to Amazon S3 - " . $e->getMessage());
        }
        // Cleanup
        try
        {
            File::delete(storage_path() . "/" . $file);
        } catch (Exception $e)
        {
            info("Could not remove $file - " . $e->getMessage());
        }
    }

    /**
     * Use the Amazon S3 Driver in Laravel for Database data
     * @return void
     */
    public function backupDatabase(): void
    {
        if (!isset($this->config->s3_key) || !$this->config->s3_key) return; // Enabled but not configured.
        // Build Storage Driver
        $s3 = $this->setS3();
        $location = sprintf("%s/%s/", "backups", now()->format("M-Y"));
        $date = now()->format("Y-m-d");
        $user = trim(env('DB_USERNAME'));
        $password = trim(env('DB_PASSWORD'));
        $db = trim(env('DB_DATABASE'));
        info("Dumping Logic Database..");
        $file = "db-$date.sql";
        $storage = storage_path() . "/";
        $command = Process::fromShellCommandline("/usr/bin/mysqldump -u $user -p{$password} $db > {$storage}{$file}");
        $command->run();
        if (!$command->isSuccessful())
        {
            error_log($command->getErrorOutput());
        }
        info("Compressing Database..");
        $command = Process::fromShellCommandline("/usr/bin/gzip {$storage}{$file}");
        $command->run();
        if (!$command->isSuccessful())
        {
            error_log($command->getErrorOutput());
        }

        try
        {
            info("Attempting to Store Database Backup to S3..");
            $s3->put($location . $file . ".gz", file_get_contents($storage . $file . ".gz"));
        } catch (Exception $e)
        {
            error_log("Failed to Upload to Amazon S3 - " . $e->getMessage());
        }
        // Cleanup
        try
        {
            File::delete($storage . $file . ".gz");
        } catch (Exception $e)
        {
            info("Could not remove $file - " . $e->getMessage());
        }
    }

    /**
     * Configure S3 Storage Engine
     * @return Filesystem
     */
    private function setS3(): Filesystem
    {
        return Storage::build([
            'driver'                  => 's3',
            'key'                     => $this->config->s3_key,
            'secret'                  => $this->config->s3_secret,
            'region'                  => $this->config->s3_region,
            'bucket'                  => $this->config->s3_bucket,
            'use_path_style_endpoint' => false,
            'throw'                   => true
        ]);
    }


}
