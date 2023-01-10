<?php

namespace App\Mail;

use App\Models\Setting;
use App\Structs\SEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class BaseMailer extends Mailable
{
    use Queueable, SerializesModels;

    public SEmail $email;

    /**
     * Create a new message instance.
     *
     * @param SEmail $email
     */
    public function __construct(SEmail $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // We need to reconfigure the mailer on each run because there may be
        // instances where we send an abuse email through one connection, and
        // some other random email through another. So just set our config
        // here each time.
        Setting::buildMailer();
        foreach ($this->email->attachments as $attachment)
        {
            $this->attach($attachment);
        }
        $content = view($this->email->view)->with('data', $this->email->viewData);
        return $this->subject($this->email->subject)->cc($this->email->cc)
            ->view('emails.base')->with('content', $content);
    }
}
