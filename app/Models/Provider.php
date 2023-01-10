<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $id
 * @property mixed $password
 * @property mixed $client_id
 * @property mixed $client_secret
 * @property mixed $username
 * @property mixed $endpoint
 * @property mixed $enabled
 * @property mixed $territory
 */
class Provider extends Model
{
    protected $guarded = ['id'];

    /**
     * Lets us know if all information has been provided.
     * @return bool
     */
    public function getHasAuthAttribute():bool
    {
        return $this->client_id && $this->client_secret && $this->username && $this->password;
    }

}
