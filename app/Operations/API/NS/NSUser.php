<?php

namespace App\Operations\API\NS;

use GuzzleHttp\Exception\GuzzleException;

class NSUser extends NSCore
{
    /**
     * List all users in a domain
     * @param string $domain
     * @return array
     * @throws GuzzleException
     */
    public function list(string $domain): array
    {
        $result = $this->send("?", 'GET', [
            'object' => 'subscriber',
            'domain' => $domain,
            'format' => 'json',
            'action' => 'list'
        ]);
        if (empty($result)) $result = [];
        return $result;
    }

}
