<?php

namespace App\Operations\API;

use App\Exceptions\LogicException;
use GuzzleHttp\Exception\GuzzleException;

class LogicEnterprise extends APICore
{
    /**
     * Logic Enterprise Manager - For Paid Logic Installations
     * @var string
     */
    public string $base = "https://my.logic.host/api/v1/";

    /**
     * Get Initial Configuration
     * @return mixed
     * @throws LogicException
     * @throws GuzzleException
     */
    public function getConfig(): mixed
    {
        $hostname = env('APP_URL');
        $hostname = str_replace("https://", null, $hostname);
        $hostname = str_replace("http://", null, $hostname);
        $hostname = explode(".", $hostname);
        $hostname = $hostname[0];
        info($hostname);
        return $this->send($this->base . "enterprise/config", 'post', [
            'hostname' => $hostname
        ]);
    }


}
