<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Setting extends Model
{
    protected $guarded = ['id'];

    /**
     * Format select values for forms.
     * @return array
     */
    public function getSelectOptsAttribute(): array
    {
        $data = [];
        $x = explode(",", $this->opts);
        foreach ($x as $v)
        {
            $data[$v] = $v;
        }
        return $data;
    }

    /**
     * Build our mailer configs
     * @return void
     */
    static public function buildMailer() : void
    {
        if (env('APP_ENV') != 'local' && setting('mail.host'))
        {
            // Only in production.
            $trans = strtolower(setting('mail.type'));
            Config::set('mail.default', $trans);
            if ($trans == 'smtp')
            {
                Config::set('mail.mailers.smtp', [
                    'transport'   => 'smtp',
                    'host'        => setting('mail.host'),
                    'port'        => setting('mail.port'),
                    'encryption'  => strtolower(setting('mail.enc')) == 'none' ? '' : strtolower(setting('mail.enc')),
                    'username'    => setting('mail.username'),
                    'password'    => setting('mail.password'),
                    'timeout'     => null,
                    'auth_mode'   => null,
                    'verify_peer' => false
                ]);
            }
            Config::set('mail.from', [
                'address' => setting('mail.fromemail'),
                'name'    => setting('mail.fromname')
            ]);
            if ($trans == 'mailgun')
            {
                Config::set('services.mailgun', [
                    'domain'   => setting('mail.mgdomain'),
                    'secret'   => setting('mail.mgsecret'),
                    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
                    'scheme'   => 'https'
                ]);
            }
        }
    }

}
