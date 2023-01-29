<?php

namespace App\Operations\API\QBO;

use App\Enums\Core\IntegrationRegistry;
use App\Exceptions\LogicException;
use App\Models\Integration;
use App\Operations\API\APICore;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use QuickBooksOnline\Payments\OAuth\OAuth2Authenticator;
use QuickBooksOnline\Payments\PaymentClient;

class  QBOCore extends APICore
{
    const REFRESH_TOKEN = 'QBO_RTOKEN';
    const ACCESS_TOKEN  = 'QBO_ATOKEN';
    const MINOR_VERSION = 65;

    public PaymentClient       $qclient;
    public OAuth2Authenticator $authenticator;
    public string              $mode;
    public string              $scope = "com.intuit.quickbooks.accounting";
    public ?string             $cid;
    public object $config;

    /**
     * Build and setup keys
     */
    public function __construct(object $config)
    {
        parent::__construct();
        if (!isset($config->qbo_client_id) || !$config->qbo_client_id) return;
        $this->qclient = new PaymentClient();
        $this->mode = env('APP_ENV') == 'local' ? 'sandbox' : 'production';
        $this->config = $config;
        $mode = $this->mode;
        $this->authenticator = OAuth2Authenticator::create([
            'client_id'     => $config->qbo_client_id,
            'client_secret' => $config->qbo_client_secret,
            'redirect_uri'  => $this->generateRedirect(),       // Where does QBO redirect when authorized?
            'environment'   => $mode
        ]);
        if (!cache(self::ACCESS_TOKEN) && cache(SELF::REFRESH_TOKEN))
        {
            // If we don't have an access token anymore but do have a refresh
            // token, then use the refresh token to get a new access token.
            $this->refreshAccessToken();
        }
    }

    /**
     * Get redirect url based on environment and keys.
     * THis will be generated by the authenticator
     *
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->authenticator->generateAuthCodeURL($this->scope);
    }

    /**
     * Generate the redirect url using settings and our mode.
     * @return string
     */
    private function generateRedirect(): string
    {
        return $this->mode == 'sandbox'
            ? env('QBO_SANDBOX_CALLBACK')
            : setting('brand.url') . "/oa/qbo/callback";
    }

    /**
     * After app is authorized, this will give us our keys.
     * @param Request $request
     * @return void
     * @throws LogicException
     */
    public function processCallback(Request $request): void
    {
        $this->acceptCode($request->code, $request->realmId);
    }

    /**
     * When we sign in to QBO, it will return a code, this code this is
     * accepted here, and we will exchange it for keys.
     * @param mixed $code
     * @throws LogicException
     */
    public function acceptCode(string $code, string $realmId)
    {
        $req = $this->authenticator->createRequestToExchange($code);
        $res = $this->qclient->send($req);
        if ($res->failed())
        {
            $errorMessage = $res->getBody();
            throw new LogicException($errorMessage);
        }
        else
        {
            //Get the keys
            $data = json_decode($res->getBody());
            cache([
                self::REFRESH_TOKEN => $data->refresh_token,
            ], Carbon::now()->addSeconds(8726400));
            cache([
                self::ACCESS_TOKEN => $data->access_token,
            ], Carbon::now()->addSeconds(3600));
            //info("Refresh: " . $data->refresh_token);
            //info("Access: " . $data->access_token);
            // Set the company id from the request
            $i = Integration::where('ident', 'qbo')->first();
            $data = $i->unpacked;
            $data->qbo_cid = $realmId;
            $i->update(['data' => $data]);
        }
    }

    /**
     * Form a request to quickbooks
     * @param string $endpoint
     * @param string $method
     * @param array  $params
     * @return mixed
     * @throws GuzzleException|LogicException
     */
    public function qsend(string $endpoint, string $method = 'get', array $params = []) : mixed
    {
        //   $params['minorversion'] = self::MINOR_VERSION;
        $this->setHeaders([
            'Authorization' => 'Bearer ' . cache(SELF::ACCESS_TOKEN),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ]);
        $base = $this->mode == 'sandbox'
            ? "https://sandbox-quickbooks.api.intuit.com/v3/company/{$this->config->qbo_cid}/"
            : "https://quickbooks.api.intuit.com/v3/company/{$this->config->qbo_cid}/";
        return $this->send($base . $endpoint, $method, $params);
    }

    /**
     * Refresh Access Token
     * @return void
     * @throws LogicException
     */
    private function refreshAccessToken(): void
    {
        $req = $this->authenticator->createRequestToRefresh(cache(self::REFRESH_TOKEN));
        $res = $this->qclient->send($req);
        $data = json_decode($res->getBody());
        if (!isset($data->refresh_token))
        {
            // Something went wrong clear out everything.
            info("No Refresh Token found in response. Here's what we have. -- " . print_r($data, true));
            Cache::forget(self::REFRESH_TOKEN);
            Cache::forget(self::ACCESS_TOKEN);
            $link = "<a href='" . setting('brand.url') . "/oa/qbo/authorize'>click to reauthorize</a>";
            throw new LogicException("Unable to get token from Quickbooks via Refresh Request. Reauthorize QBO: " . $link);
        }
        cache([
            self::REFRESH_TOKEN => $data->refresh_token,
        ], Carbon::now()->addSeconds(8726400));
        cache([
            self::ACCESS_TOKEN => $data->access_token ?? '',
        ], Carbon::now()->addSeconds(3600));
    }

    /**
     * Query for a single or multiple records.
     * @param string     $object
     * @param string     $property
     * @param string|int $matches
     * @param bool       $single
     * @return mixed
     * @throws GuzzleException|LogicException
     */
    public function query(string $object, string $property, string|int $matches, bool $single = true): mixed
    {
        $q = sprintf("SELECT * from %s WHERE %s = '%s'", $object, $property, $matches);
        $res = $this->qsend("query", 'get', ['query' => $q]);
        if (isset($res->QueryResponse->{$object}))
        {
            return $single ? $res->QueryResponse->{$object}[0] : $res->QueryResponse->$object;
        }
        return null;
    }


}
