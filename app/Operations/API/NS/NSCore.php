<?php

namespace App\Operations\API\NS;


use Exception;
use Illuminate\Support\Facades\Cache;
use LogicException;
use App\Models\Provider;
use App\Operations\API\APICore;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

class NSCore extends APICore
{

    public Provider $provider;                                   // Logic Provider
    public ?string  $token;                                      // Oauth Access Token
    public Client   $client;


    /**
     * Instantiate a NS API Instance with a Provider
     * @param Provider $provider
     * @throws GuzzleException
     */
    public function __construct(Provider $provider)
    {
        parent::__construct();
        $this->provider = $provider;
        $this->token = $this->getAccessToken();
        $this->setHeaders(['Content-Type' => 'application/json']);
    }


    /**
     * Netsapiens Client Overrides
     * @param string $endpoint
     * @param string $method
     * @param array  $params
     * @return mixed
     * @throws GuzzleException
     */
    public function send(string $endpoint, string $method = 'get', array $params = []): mixed
    {
        $base = $this->provider->endpoint;
        if (!Str::endsWith($base, "/")) $base .= "/";
        $endpoint = $base . $endpoint;
        if (isset($this->token))
        {
            $this->setHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]);
        }
        return parent::send($endpoint, $method, $params);
    }


    /**
     * Returns an access token either from cache or gets a new one
     * from the NSAPI.
     * https://manage.dialpath.com/ns-api/oauth2/token/?grant_type=password&client_id=dpapi&client_secret=188c2212c17978636e54e9010b6adcbb&username=2020@dialpath.com&password=Jus7zetu1
     * @param bool $force
     * @return string
     * @throws GuzzleException
     */
    private function getAccessToken(bool $force = false): string
    {
        if (cache("PID_{$this->provider->id}_OAUTH") && isset(cache("PID_{$this->provider->id}_OAUTH")->access_token))
        {
            return cache("PID_{$this->provider->id}_OAUTH")->access_token;
        }
        info("No Access token found.. Checking for Refresh Token Existence.. ");
        if (cache("PID_{$this->provider->id}_REFRESH") && !$force)
        {
            info("Attempting to use NS Refresh token to get a new access token..");
            return $this->useRefreshToken();
        }

        try
        {
            $tokenResponse = $this->send("oauth2/token", 'GET', [
                'grant_type'    => "password",
                'client_id'     => $this->provider->client_id,
                'client_secret' => $this->provider->client_secret,
                'username'      => $this->provider->username,
                'password'      => $this->provider->password
            ]);
            if (!isset($tokenResponse->refresh_token))
            {
                info("No Token Found in Response - Clearing oAuth Cache...");
                Cache::forget("PID_{$this->provider->id}_OAUTH");
                Cache::forget("PID_{$this->provider->id}_REFRESH");
                throw new LogicException("Unable to get Access Token; reauthorization required.");
            }
        } catch (GuzzleException $e)
        {
            // Everything failed. Lets make sure we clear out any bad data and make it fully reauth.
            Cache::forget("PID_{$this->provider->id}_OAUTH");
            Cache::forget("PID_{$this->provider->id}_REFRESH");
            info("Failed to get Token: " . $e->getMessage());
            throw new LogicException($e->getMessage());
        }
        cache(["PID_{$this->provider->id}_OAUTH" => $tokenResponse], now()->addMinutes(59));
        cache(["PID_{$this->provider->id}_REFRESH" => $tokenResponse->refresh_token]);
        return $tokenResponse->access_token ?? '';
    }

    /**
     * Our Access Token has expired but we have a refresh token.
     * @return string
     * @throws GuzzleException
     */
    private function useRefreshToken(): string
    {
        $rt = cache("PID_{$this->provider->id}_REFRESH");
        try
        {
            $tokenResponse = $this->send("oauth2/token", 'GET', [
                'grant_type'    => 'refresh_token',
                'client_id'     => $this->provider->client_id,
                'client_secret' => $this->provider->client_secret,
                'refresh_token' => $rt
            ]);
        } catch (Exception $e)
        {
            // The refresh token may have expired. Try to auth again plain.
            Cache::forget("PID_{$this->provider->id}_OAUTH");
            Cache::forget("PID_{$this->provider->id}_REFRESH");
            info("Failed to get access token from refresh: " . $e->getMessage());
            return $this->getAccessToken(true);
        }
        cache(["PID_{$this->provider->id}_OAUTH" => $tokenResponse], now()->addMinutes(59));
        cache(["PID_{$this->provider->id}_REFRESH" => $tokenResponse->refresh_token]);
        return $tokenResponse->access_token;
    }
}
