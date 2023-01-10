<?php

namespace App\Operations\Integrations\Support;

use App\Enums\Core\IntegrationRegistry;
use App\Models\Account;
use App\Models\User;
use App\Operations\API\Zendesk\ZDCore;
use App\Operations\Integrations\BaseIntegration;
use App\Operations\Integrations\Integration;
use GuzzleHttp\Exception\GuzzleException;

class Zendesk extends BaseIntegration implements Integration
{
    public IntegrationRegistry $ident = IntegrationRegistry::Zendesk;
    public ZDCore              $zd;

    /**
     * Init Zendesk API
     */
    public function __construct()
    {
        parent::__construct();
        $this->zd = new ZDCore($this->config->zendesk_hostname,
            $this->config->zendesk_email,
            $this->config->zendesk_token);
    }

    public function getName(): string
    {
        return "Zendesk";
    }

    public function getWebsite(): string
    {
        return "https://www.zendesk.com";
    }

    public function getDescription(): string
    {
        return "Zendesk makes customer service better. We build software to meet customer needs,
        set your team up for success, and keep your business in sync.";
    }

    public function getLogo(): string
    {
        return "https://www.vectorlogo.zone/logos/zendesk/zendesk-ar21.png";
    }

    /**
     * Get required configuration
     * @return array
     */
    public function getRequired(): array
    {
        return [
            (object)[
                'var'         => 'zendesk_hostname',
                'item'        => "Zendesk Hostname:",
                'description' => "Enter your zendesk instance name (before .zendesk.com)",
                'default'     => '',
                'protected'   => false,
            ],
            (object)[
                'var'         => 'zendesk_email',
                'item'        => "Zendesk E-mail:",
                'description' => "Enter the account email Logic will send from",
                'default'     => '',
                'protected'   => false,
            ],
            (object)[
                'var'         => 'zendesk_token',
                'item'        => "Zendesk Token:",
                'description' => "Enter the API Token (v2)",
                'default'     => '',
                'protected'   => true,
            ],
        ];
    }

    /** --------------- End of Configuration -------------- */

    /**
     * Find organization by name
     * @param string $name
     * @return mixed
     * @throws GuzzleException
     */
    public function findOrgIdByName(string $name): mixed
    {
        $org = $this->zd->findOrgByName($name);
        if (isset($org) && $org->id) return $org->id;
        return null;
    }

    /**
     * Create an organization and return the applicable ID
     * @param Account $account
     * @return mixed
     * @throws GuzzleException
     */
    public function createOrganization(Account $account): mixed
    {
        return $this->zd->createOrganization($account->name);
    }

    /**
     * Update Organization Record
     * @param Account $account
     * @return void
     * @throws GuzzleException
     */
    public function updateOrganization(Account $account): void
    {
        $this->zd->updateOrganization($account->support_organization_id, $account->name);
    }

    /**
     * Find a user by its email address.
     * @param User $user
     * @return mixed
     * @throws GuzzleException
     */
    public function findUserByEmail(User $user): mixed
    {
        return $this->zd->findUserByEmail($user->email);
    }

    /**
     * Create user and return the resulting id.
     * @param User $user
     * @return mixed
     * @throws GuzzleException
     */
    public function createUser(User $user): mixed
    {
        return $this->zd->createUser($user->name, $user->email, $user->account->support_organization_id);
    }

    /**
     * Update a user's name and email address.
     * @param User $user
     * @return void
     * @throws GuzzleException
     */
    public function updateUser(User $user): void
    {
        $this->zd->updateUser($user->support_user_id, $user->name, $user->email);
    }

    /**
     * Create a new Ticket with Zendesk
     * @param User   $user
     * @param string $subject
     * @param string $body
     * @param bool   $html
     * @return mixed
     * @throws GuzzleException
     */
    public function createTicket(User $user, string $subject, string $body, bool $html): mixed
    {
        return $this->zd->createTicket($user->support_user_id,
            $user->account->support_organization_id,
            $subject, $body, $html);
    }

    /**
     * Update ticket at Zendesk
     * @param string $ticket
     * @param string $body
     * @param User   $author
     * @return void
     * @throws GuzzleException
     */
    public function updateTicket(string $ticket, string $body, User $author): void
    {
        $this->zd->updateTicket($ticket, $body, $author);
    }

    /**
     * Get Ticket URL based on id given.
     * @param string $ticketid
     * @return string
     */
    public function getTicketUrl(string $ticketid) : string
    {
        return sprintf("https://%s.zendesk.com/agent/tickets/%s", $this->config->zendesk_hostname, $ticketid);
    }

}
