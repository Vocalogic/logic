<?php

namespace App\Structs;

/**
 * Email Structs
 *
 * This will contain a simple object that can be passed to a mailer with all the
 * required information, attachments, cc/bcc etc, with the views required.
 *
 * @package App\Structs
 */
class SEmail
{
    /**
     * SEmail constructor.
     * @param string $subject     The subject of the email
     * @param string $toEmail     The destination email address || a set of comma seperated emails,
     *                            the first one will be the primary email and the rest will go as cc emails.
     * @param string $toName      The destination name
     * @param string $view        The email view that should extend the mailer
     * @param array  $cc          An array of email -> name for cc
     * @param array  $bcc         An array of bcc emails -> name for bcc
     * @param array  $attachments An array of attachments
     * @param array  $viewData    An array of data for the view
     */
    public function __construct(
        public string $subject,
        public string $toEmail,
        public string $toName,
        public string $view,
        public array $cc = [],
        public array $bcc = [],
        public array $attachments = [],
        public array $viewData = []

    ) {
        $this->extractCarbonCopyEmailAddresses();
    }

    /**
     * Checks the given toEmail parameter if it contains multiple comma separated emails,
     * and extracts the extra emails into cc array after validating the emails.
     */
    public function extractCarbonCopyEmailAddresses()
    {
        $emails = collect(explode(',', $this->toEmail))->map(function ($email) {
            $email = trim($email);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) return $email;
        })->filter(function ($email) {
            if ($email) return $email;
        })->toArray();
        $this->toEmail = $emails[0] ?? '';
        $this->cc = array_merge($this->cc, array_slice($emails, 1));
    }

}
