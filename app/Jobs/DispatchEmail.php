<?php

namespace App\Jobs;

use App\Enums\Core\EventType;
use App\Mail\BaseMailer;
use App\Models\Setting;
use App\Structs\SEmail;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class DispatchEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public SEmail $email;

    /**
     * Create a new job instance.
     *
     * @param SEmail $email
     */
    public function __construct(SEmail $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Setting::buildMailer();
        if (!$this->email->toEmail)
        {
            info("No valid email found");
            return;
        }

        (new MailServiceProvider(app()))->register();
        info("Attempting to Send email to {$this->email->toName} at {$this->email->toEmail}");
        try {
            Mail::to([$this->email->toName => $this->email->toEmail])->cc($this->email->cc)
                ->queue(new BaseMailer($this->email));
        } catch(Exception $e)
        {
            info("Failure to send email: " . $e->getMessage());
            _log(EventType::Mail, EventType::SEV_ERROR, $e->getMessage());
        }
    }
}
