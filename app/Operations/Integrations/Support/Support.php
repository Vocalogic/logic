<?php

namespace App\Operations\Integrations\Support;

use App\Enums\Core\IntegrationType;
use App\Models\Account;
use App\Models\User;

class Support
{
    public IntegrationType $type = IntegrationType::Support;

    /**
     * Create or Update an account/org with our integration.
     * @param Account $account
     * @return void
     */
    public static function syncAccount(Account $account) : void
    {
        if (!$account->admin) return;
        $x = new self;
        if (!$account->support_organization_id)
        {
            $org = $x->findOrgIdByName($account->name);
            if ($org)
            {
                $account->update(['support_organization_id' => $org]);
                $account->refresh();
            }
            else
            {
                $account->update(['support_organization_id' => $x->createOrganization($account)]);
                return;
            }
        }
        $x->updateOrganization($account);
    }

    /**
     * Sync user to remote support system.
     * @param User $user
     * @return void
     */
    static public function syncUser(User $user):void
    {
        $x = new self;
        if (!$user->support_user_id)
        {
            $uid = $x->findUserByEmail($user);
            if ($uid)
            {
                $user->update(['support_user_id' => $uid]);
            }
            else
            {
                $user->update(['support_user_id' => $x->createUser($user)]);
                return;
            }
        }
        $x->updateUser($user);
    }

    /**
     * Find organization by name
     * @param string $name
     * @return mixed
     * @throws GuzzleException
     */
    public function findOrgIdByName(string $name): mixed
    {
        return getIntegration($this->type)->connect()->findOrgIdByName($name);
    }

    /**
     * Create an organization and return the applicable ID
     * @param Account $account
     * @return mixed
     */
    public function createOrganization(Account $account):mixed
    {
        return getIntegration($this->type)->connect()->createOrganization($account);
    }

    /**
     * Update Organization Record
     * @param Account $account
     * @return void
     */
    public function updateOrganization(Account $account):void
    {
        getIntegration($this->type)->connect()->updateOrganization($account);
    }

    // Users

    /**
     * Get a user id by email address.
     * @param User $user
     * @return mixed
     */
    public function findUserByEmail(User $user) : mixed
    {
        return getIntegration($this->type)->connect()->findUserByEmail($user);
    }

    /**
     * Create a new user
     * @param User $user
     * @return mixed
     */
    public function createUser(User $user): mixed
    {
        if (!$user->account->support_organization_id)
        {
            $id = $this->createOrganization($user->account);
            $user->account()->update(['support_organization_id' => $id]);
            $user->account->refresh();
            $user->refresh();
        }
        return getIntegration($this->type)->connect()->createUser($user);
    }

    /**
     * Update User Info with Support Service
     * @param User $user
     * @return mixed
     */
    public function updateUser(User $user): mixed
    {
        return getIntegration($this->type)->connect()->updateUser($user);
    }

    /**
     * Create a new Ticket with Vendor
     * @param User   $user
     * @param string $subject
     * @param string $body
     * @param bool   $html
     * @return mixed
     */
    public function createTicket(User $user, string $subject, string $body, bool $html = false) : mixed
    {
        if (!$user->support_user_id)
        {
           self::syncUser($user);
           $user->refresh();
        }
        return getIntegration($this->type)->connect()->createTicket($user, $subject, $body, $html);
    }

    /**
     * Update ticket
     * @param string $ticket
     * @param string $body
     * @param User   $author
     * @return mixed
     */
    public function updateTicket(string $ticket, string $body, User $author): mixed
    {
        return getIntegration($this->type)->connect()->updateTicket($ticket, $body, $author);
    }

    /**
     * Get Ticket URL based on id given.
     * @param string $ticketid
     * @return string
     */
    public function getTicketUrl(string $ticketid) : string
    {
        return getIntegration($this->type)->connect()->getTicketUrl($ticketid);
    }

}
