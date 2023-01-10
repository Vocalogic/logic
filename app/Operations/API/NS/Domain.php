<?php

namespace App\Operations\API\NS;

use GuzzleHttp\Exception\GuzzleException;

class Domain extends NSCore
{
    /**
     * Return a total count of Domains
     * @return int
     * @throws GuzzleException
     */
    public function count(): int
    {
        $result = $this->send("?", 'GET', [
            'object' => 'domain',
            'action' => 'count',
            'format' => 'json'
        ]);
        return (int)$result->total;
    }

    /**
     * Get a list of all domains.
     * @return array
     * @throws GuzzleException
     */
    public function all(): array
    {
        $result = $this->send("?", 'GET', [
            'object' => 'domain',
            'action' => 'read',
            'format' => 'json'
        ]);
        return $result;
    }

    /**
     * Get billable information for a domain
     * @param string $domain
     * @return mixed
     * @throws GuzzleException
     */
    public function billable(string $domain): mixed
    {
        $result = $this->send("?", 'GET', [
            'object'  => 'domain',
            'action'  => 'read',
            'billing' => 'yes',
            'domain'  => $domain,
            'format'  => 'json'
        ]);
        return $result;
    }


    /**
     * Create a new domain
     * @param string $domain
     * @param string $description
     * @return void
     * @throws GuzzleException
     */
    public function create(string $domain, string $description)
    {
        $this->send("?", 'GET', [
            'object'      => 'domain',
            'action'      => 'create',
            'domain'      => sprintf("%s.%s", $domain, $this->provider->territory),
            'territory'   => $this->provider->territory,
            'description' => $description
        ]);
    }

}
