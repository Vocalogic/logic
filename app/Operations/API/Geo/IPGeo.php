<?php

namespace App\Operations\API\Geo;

use App\Enums\Core\CommKey;
use App\Exceptions\LogicException;
use App\Jobs\GetIPJob;
use App\Operations\API\APICore;
use GuzzleHttp\Exception\GuzzleException;

class IPGeo extends APICore
{
    public string $endpoint = "http://ip-api.com/json/";

    /**
     * Attempt to lookup IP.
     * @param string $ip
     * @return object|null
     */
    public function get(string $ip): ?object
    {
        $ipCache = cache(CommKey::GlobalIPInventoryCache->value) ?: [];
        if (!array_key_exists($ip, $ipCache))
        {
            dispatch(new GetIPJob($ip));
            return null;
        }
        else
        {
            return $ipCache[$ip];
        }
    }

    /**
     * Called from a job to update the ip cache if we have an ip result.
     * @param string $ip
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    static public function attempt(string $ip) : void
    {
        $x = new self;
        $ipCache = cache(CommKey::GlobalIPInventoryCache->value) ?: [];
        $result = $x->send($x->endpoint . $ip);
        $ipCache[$ip] = $result;
        cache([CommKey::GlobalIPInventoryCache->value => $ipCache], CommKey::GlobalIPInventoryCache->getLifeTime());
    }


}
