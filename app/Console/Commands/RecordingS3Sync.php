<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\CallRecording;
use Aws\S3\Exception\S3Exception;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Prophecy\Call\Call;

class RecordingS3Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recordings:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all recordings to S3';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (Account::whereNotNull('s3_secret')->get() as $account)
        {
            $this->info("Scanning $account->name..");
            // Set our configuration real quick to use our fake file systme.
            $s3 = $this->setS3($account);
            foreach ($account->recordings()->whereNull('s3_url')->get() as $call)
            {
                $name = Str::slug($this->formatCall($call));
                $data = $this->getRecording($call);
                if (!$data)
                {
                    // There is no recording for this. But don't keep processing
                    $call->update(['s3_url' => "#"]);
                }
                // Ok we have call data.
                $location = sprintf("%s/%s/%s/%s/",
                    $account->s3_prefix,
                    $call->time_open->format("Y"),
                    $call->time_open->format("m"),
                    $call->time_open->format("d")
                );
                try
                {
                    $this->info("Attempting to Write {$location}$name.wav - " . strlen($data) . " bytes..");
                    $s3->put($location . "$name.wav", base64_decode($data));
                } catch (\RuntimeException $e)
                {
                    $this->alert('Failed to Store to S3: ' . $e->getMessage());
                }
                $call->update(['s3_url' => "https://s3.amazonaws.com/$account->s3_bucket/$location/$name.wav"]);
            }
        }
        return 0;
    }

    /**
     * Configure S3 for Account.
     * @param mixed $account
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    private function setS3(mixed $account): \Illuminate\Contracts\Filesystem\Filesystem
    {
        return Storage::build([
            'driver'                  => 's3',
            'key'                     => $account->s3_key,
            'secret'                  => $account->s3_secret,
            'region'                  => $account->s3_region,
            'bucket'                  => $account->s3_bucket,
            'use_path_style_endpoint' => false,
            'throw'                   => true
        ]);
    }

    /**
     * Determine the name of the file based on the call data.
     * @param CallRecording $call
     * @return string
     */
    private function formatCall(CallRecording $call): string
    {
        return sprintf("%s-%s-%s", $call->time_open->format("Y-m-d-H-i"), $call->to, $call->from);
    }

    /**
     * Get file and base64 encode it for decompilation to S3
     * @param CallRecording $call
     * @return string|null
     */
    private function getRecording(CallRecording $call): ?string
    {
        $context = [
            "ssl" => [
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ]
        ];

        try
        {
            $x = file_get_contents($call->url, false, stream_context_create($context));
        } catch (Exception $e)
        {
            info("Failed to get File: " . $e->getMessage());
            return null;
        }
        if ($x)
        {
            return base64_encode($x);
        }
        else return null;
    }
}
