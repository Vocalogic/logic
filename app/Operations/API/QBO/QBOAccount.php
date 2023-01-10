<?php

namespace App\Operations\API\QBO;

use GuzzleHttp\Exception\GuzzleException;

class QBOAccount extends QBOCore
{

    /**
     * Get a list of all accounts.
     * @return mixed
     * @throws GuzzleException
     */
    public function all(): mixed
    {
        return $this->qsend("query", 'get', [
            'query' => 'SELECT * from Account'
        ]);
    }

    /**
     * Find account by ID
     * @param int $id
     * @return mixed
     * @throws GuzzleException
     */
    public function find(int $id) : mixed
    {
        return $this->qsend("query", 'get', [
            'query' => "SELECT * from Account where Id = $id"
        ]);
    }

    /**
     * Lookup Account by name.
     * @param string $name
     * @return mixed
     * @throws GuzzleException
     */
    public function findByName(string $name) : mixed
    {
        return $this->qsend("query", 'get', [
            'query' => "SELECT * from Account where Name like '%$name%'"
        ]);
    }


}
