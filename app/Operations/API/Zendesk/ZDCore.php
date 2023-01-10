<?php

namespace App\Operations\API\Zendesk;

use App\Operations\API\APICore;
use GuzzleHttp\Exception\GuzzleException;

class ZDCore extends APICore
{

    public string $host;
    public string $email;
    public string $token;


    /**
     * @param string $host
     * @param string $email
     * @param string $token
     */
    public function __construct(string $host, string $email, string $token)
    {
        parent::__construct();
        $this->host = $host;
        $this->email = $email;
        $this->token = $token;
        $this->initHeaders();

    }

    /**
     * Sets up the headers and url of the api.
     */
    private function initHeaders(): void
    {
        $token = base64_encode(sprintf("%s/token:%s", $this->email, $this->token));
        $this->setHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => "Basic $token",
            'Accept'        => 'application/json; ident=4'
        ]);
    }

    /**
     * Send API call to Zendesk
     * @param string $endpoint
     * @param string $method
     * @param array  $params
     * @return mixed
     * @throws GuzzleException
     */
    public function zdsend(string $endpoint, string $method = 'get', array $params = []): mixed
    {
        $baseUrl = sprintf("https://%s.zendesk.com/api/v2/", $this->host);
        return $this->send($baseUrl . $endpoint, $method, $params);
    }


    /**
     * Get organization by org id
     * @param string $organizationID
     * @return mixed
     * @throws GuzzleException
     */
    public function findOrgById(string $organizationID): mixed
    {
        $res = $this->zdsend("organizations/$organizationID");
        if (isset($res->organization) && isset($res->organization->id))
        {
            return $res->organization;
        }
        return null;
    }

    /**
     * Searches for organizations by name and gets the first match or null.
     * @param string $name
     * @return mixed
     * @throws GuzzleException
     */
    public function findOrgByName(string $name): mixed
    {
        $res = $this->zdsend("search", 'get',
            ['query' => "type:organization name:$name"]);
        if (isset($res->results) && is_array($res->results) && isset($res->results[0]))
        {
            return $res->results[0];
        }
        return null;
    }

    /**
     * Create an organization and return the id.
     * @param string $name
     * @return mixed
     * @throws GuzzleException
     */
    public function createOrganization(string $name): mixed
    {
        info("Creating ORG $name");
        $org = $this->zdsend("organizations", 'post', [
            'organization' => [
                'name' => $name,
            ]
        ]);
        if (isset($org->organization) && isset($org->organization->id))
        {
            return $org->organization->id;
        }
        return null;
    }

    /**
     * Update Organization Name/Details
     * @param string|int $orgid
     * @param string     $name
     * @return void
     * @throws GuzzleException
     */
    public function updateOrganization(string|int $orgid, string $name): void
    {
        $this->zdsend("organizations/$orgid", 'put', [
            'organization' => [
                'name' => $name
            ]
        ]);
    }

    /**
     * Get a user by email
     * @param string $email
     * @return mixed
     * @throws GuzzleException
     */
    public function findUserByEmail(string $email): mixed
    {
        $res = $this->zdsend("users/search", 'get', ['query' => $email]);
        if (isset($res->users) && isset($res->users[0]))
        {
            return $res->users[0]->id;
        }
        else return null;
    }

    /**
     * Create a Zendesk User.
     * @param string $name
     * @param string $email
     * @param string $orgid
     * @return mixed
     * @throws GuzzleException
     */
    public function createUser(string $name, string $email, string $orgid): mixed
    {
        $res = $this->zdsend("users", 'post', [
            'user' => [
                'name'            => $name,
                'email'           => $email,
                'verified'        => true,
                'role'            => 'end-user',
                'organization_id' => $orgid
            ]
        ]);
        if (isset($res->user) && isset($res->user->id))
        {
            return $res->user->id;
        }
        else return null;
    }

    /**
     * Update a User
     * @param mixed $uid
     * @param mixed $name
     * @param mixed $email
     * @return void
     * @throws GuzzleException
     */
    public function updateUser(mixed $uid, mixed $name, mixed $email) :void
    {
        $res = $this->zdsend("users/$uid", 'put', [
            'user' => [
                'name'            => $name,
                'email'           => $email,
            ]
        ]);
    }

    /**
     * Create a new Zendesk Ticket
     * @param string $uid     Zendesk User Id
     * @param string $orgid   Organization to assign to
     * @param string $subject Subject of the ticket
     * @param string $body    Body of the ticket
     * @param bool   $html    Set false if using a standard email template. true for tables and html stuff
     * @return mixed
     * @throws GuzzleException
     */
    public function createTicket(string $uid, string $orgid, string $subject, string $body, bool $html = false): mixed
    {
        $res = $this->zdsend("tickets", 'post', [
            'ticket' => [
                'subject'         => $subject,
                'requester_id'    => $uid,
                'organization_id' => $orgid,
                'group_id'        => null,
                'comment'         => [
                    ($html ? 'html_body' : 'body') => $body,
                    'public'                       => true
                ]
            ]
        ]);
        return $res->ticket->id;
    }

    /**
     * Update a ticket at Zendesk
     * @param string $ticketid The ticket id at zendesk
     * @param string $comment  The body of the message
     * @param string $author   The author ID of the message (logic user)
     * @return void
     * @throws GuzzleException
     */
    public function updateTicket(string $ticketid, string $comment, string $author): void
    {
        $this->zdsend("tickets/$ticketid", 'put', [
            'ticket' => [
                'comment' => [
                    'body'      => $comment,
                    'author_id' => $author,
                    'via'       => "LogicCRM"
                ]
            ]
        ]);
    }


}
