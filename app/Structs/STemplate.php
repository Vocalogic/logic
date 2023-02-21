<?php

namespace App\Structs;

use App\Jobs\DispatchEmail;
use App\Models\Brand;
use App\Models\EmailTemplate;
use App\Models\User;
use Exception;

class STemplate
{
    /**
     * The template entity
     * @var EmailTemplate
     */
    public EmailTemplate $template;

    /**
     * The loaded content body for the user's account brand.
     * @var string
     */
    public string $contentBody;


    /**
     * Create a template object to be emailed and perform a few sanity checks.
     * @param string      $ident
     * @param User|null   $user        Who is this template being sent to?
     * @param array|null  $models      What models are included in this template for parsing
     * @param array|null  $attachments What files should be attached to the email?
     * @param string|null $ccEmail     If this email should be cc'd to a lead or something
     * @param string|null $ccName      What name to send CC emailed to
     * @throws LogicException
     */
    public function __construct(
        public string $ident,
        public ?User $user = null,
        public ?array $models = [],
        public ?array $attachments = [],
        public ?string $ccEmail = null,
        public ?string $ccName = null
    ) {
        $template = EmailTemplate::whereIdent($ident)->first();
        if (!$template)
        {
            $template = new EmailTemplate();
            $template->body = $ident;
        }
        $this->template = $template;
        $this->loadContent();
        $this->parseEntity();
    }

    /**
     * This method will attempt to perform parsing based on the data given.
     */
    private function parseEntity(): void
    {
        // Ok we will use the lowercase of the modal name and check to see if it exists.
        // For instance if we sent in Account::class, and we have a {account.name} then we would look for
        // a class of type User (ucFirst) and if it exists in our models array use that variable and
        // replace the text.
        // If the model doesn't exist - then it's just blanked out. {user.} will always come from the public
        // user property in this class.
        preg_match_all("/{\s*([^}]+)\s*}/i", $this->contentBody, $keys);
        $keys = $keys[1];
        foreach ($keys as $key)
        {
            $x = explode(".", $key);
            if (!isset($x[1])) continue;
            $class = $x[0];
            $field = $x[1];
            $this->contentBody = str_replace('{' . $class . '.' . $field . '}', $this->getReplacement($class, $field),
                $this->contentBody);
        }
        preg_match_all("/{\s*([^}]+)\s*}/i", $this->template->subject, $keys);
        $keys = $keys[1];
        foreach ($keys as $key)
        {
            $x = explode(".", $key);
            if (!isset($x[1])) continue;
            $class = $x[0];
            $field = $x[1];
            $this->template->subject = str_replace('{' . $class . '.' . $field . '}',
                $this->getReplacement($class, $field),
                $this->template->subject);
        }
    }

    /**
     * Load the content based on the target user's brand.
     */
    private function loadContent(): void
    {
        $this->contentBody = $this->template->body;
    }

    /**
     * Get replacement string for the class, field that we get here.
     * @param string $class
     * @param string $field
     * @return string|null
     */
    private function getReplacement(string $class, string $field): ?string
    {
        $modeledString = ucfirst($class);
        if (str_contains($class, "setting"))
        {
            $field = str_replace("-", ".", $field);
            return setting($field);
        }
        $instance = "\\App\\Models\\{$modeledString}";
        try
        {
            if (class_exists($instance))
            {
                $instance = new $instance();
            }
            else
            {
                return null;
            }
        } catch (Exception $e)
        {
            info("Tried to use $modeledString but that class does not exist.");
            return null;
        }
        foreach ($this->models as $model)
        {
            if ($model instanceof $instance) // I mean.. this looks silly, but hey.
            {
                return $model->{$field};
            }
        }
        return null;
    }

    /**
     * Basically this is run to actually create the email.
     */
    public function fire(): void
    {
        if (!$this->template->enabled)
        {
            info("Template {$this->template->name} is disabled. Not sending email.");
            return;
        }
        if ($this->user)
        {
            $email = new SEmail(
                subject: $this->template->subject,
                toEmail: $this->user->email,
                toName: $this->user->name,
                view: 'emails.templated',
                attachments: $this->attachments,
                viewData: ['user' => $this->user, 'content' => $this->contentBody]
            );
            dispatch(new DispatchEmail($email));
        }
        if ($this->ccEmail)
        {
            $first = explode(" ", $this->ccName);
            $first = $first[0];
            $uobj = (object)['name' => $this->ccName, 'email' => $this->ccEmail, 'first' => $first];
            $email = new SEmail(
                subject: $this->template->subject,
                toEmail: $this->ccEmail,
                toName: $this->ccName,
                view: 'emails.templated',
                attachments: $this->attachments,
                viewData: ['user' => $uobj, 'content' => $this->contentBody]
            );
            dispatch(new DispatchEmail($email));
        }
    }
}
