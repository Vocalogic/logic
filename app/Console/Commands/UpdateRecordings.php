<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\CallRecording;
use App\Operations\API\NS\CDR;
use App\Operations\API\NS\Recording;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class UpdateRecordings extends Command
{
    protected int $days = 2; // Last two days.
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recordings:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll all accounts with S3 Creds and Grab Recordings';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle()
    {
        $this->alert("Beginning Recording Cycle.");
        $start = Carbon::now()->subDays($this->days);
        $end = Carbon::now();
        foreach (Account::whereNotNull('s3_secret')->get() as $account)
        {
            $this->info("Scanning $account->name..");
            $capi = new CDR($account->provider);
            $recordings = $capi->byDomain($account->pbx_domain, $start, $end);
            $count = count($recordings);
            $this->info($count . " Calls Found - Checking for Recordings.");
            foreach ($recordings as $recording)
            {
                $cdr = $capi->find($recording->cdr_id, $start, $end);
                if (CallRecording::where('cdr_id', $recording->cdr_id)->count()) continue;
                $orig = $capi->isOriginating($cdr);
                $r = new Recording($account->provider);
                if (!$orig)
                {
                    $this->info("Looking for Originating {$cdr->CdrR->orig_callid}");
                    $record = $r->get($account->pbx_domain, $cdr->CdrR->orig_callid);
                }
                else
                {
                    $this->info("Looking for Terminating {$cdr->CdrR->term_callid}");
                    $record = $r->get($account->pbx_domain, null, $cdr->CdrR->term_callid);
                }
                if (isset($record->url))
                {
                    if ($record->status == 'unconverted')
                    {
                        $this->info("Call has not converted yet.. Skipping for now.");
                        continue;
                    } // We need to get this on the next pass.
                    // We have a recorded call and its url. Check to see if it's already in the record.
                    $cr = CallRecording::where('cdr_id', $cdr->cdr_id)->first();
                    if ($cr) continue;
                    // We have a new record.
                    (new CallRecording)->create([
                        'account_id' => $account->id,
                        'status'     => $record->status,
                        'call_id'    => $record->call_id,
                        'time_open'  => $record->time_open,
                        'time_close' => $record->time_close,
                        'duration'   => $record->duration,
                        'url'        => $record->url,
                        'size'       => $record->size,
                        'cdr_id'     => $cdr->cdr_id,
                        'from'       => $cdr->orig_from_name,
                        'to'         => $cdr->orig_to_user
                    ]);
                }
            }
        }
        return 0;
    }
}
