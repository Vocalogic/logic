<?php

namespace App\Operations\API;

use App\Exceptions\LogicException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;

abstract class APICore
{

    public int    $connectionTimeout = 10;
    public array  $headers           = [];
    public array  $auth              = [];
    public int $responseCode = 0;

    public Client $client;

    /**
     * Setup Client
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Define headers to be sent with our API call.
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Set authorization if required.
     * @param array $auth
     */
    public function setAuth(array $auth): void
    {
        $this->auth = $auth;
    }



    /**
     * Send command to NS API
     * @param string $endpoint
     * @param string $method
     * @param array  $params
     * @return mixed
     * @throws GuzzleException
     * @throws LogicException
     */
    public function send(string $endpoint, string $method = 'get', array $params = []): mixed
    {
        try
        {
            $response = match ($method)
            {
                'post' => $this->client->post($endpoint, [
                    'json'            => $params,
                    'headers'         => $this->headers,
                    'verify'          => false,
                    'auth'            => $this->auth,
                    'connect_timeout' => $this->connectionTimeout
                ]),
                'put' => $this->client->put($endpoint, [
                    'json'            => $params,
                    'headers'         => $this->headers,
                    'verify'          => false,
                    'connect_timeout' => $this->connectionTimeout
                ]),
                'delete' => $this->client->delete($endpoint, [
                    'query'           => $params,
                    'headers'         => $this->headers,
                    'verify'          => false,
                    'connect_timeout' => $this->connectionTimeout
                ]),
                'patch' => $this->client->patch($endpoint, [
                    'json'            => $params,
                    'headers'         => $this->headers,
                    'verify'          => false,
                    'connect_timeout' => $this->connectionTimeout
                ]),
                default => $this->client->get($endpoint, [
                    'query'           => $params,
                    'headers'         => $this->headers,
                    'verify'          => false,
                    'auth'            => $this->auth,
                    'connect_timeout' => $this->connectionTimeout
                ]),
            };
        } catch (ClientException|RequestException $e)
        {
            $this->responseCode = $e->getResponse()->getStatusCode();
            info($e->getMessage());
            info(sprintf("Debug Log Failure: %s - File: %s, Line %d", $e->getResponse()->getBody(), $e->getFile(),
                $e->getLine()));
            info("Body: ". $e->getResponse()->getBody());
            throw new LogicException($e->getResponse()->getBody());
        }
        return json_decode($response->getBody()->getContents());
    }

}
