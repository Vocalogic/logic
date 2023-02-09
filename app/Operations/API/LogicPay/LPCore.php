<?php

namespace App\Operations\API\LogicPay;

use App\Exceptions\LogicException;
use App\Models\Integration;
use App\Operations\API\APICore;
use GuzzleHttp\Exception\GuzzleException;

/**
 * LogicPay/CardPointe Integration
 */
class LPCore extends APICore
{
    public string      $mid;
    public string      $user;
    public string      $password;
    public Integration $integration;
    public string      $baseUrl = '';

    public string $baseUrlTesting = "https://isv-uat.cardconnect.com/cardconnect/"; // Generic Testing
    public string $baseUrlProd    = "https://isv.cardconnect.com/cardconnect/";        // Production


    /**
     * Prepare LogicPay API
     */
    public function __construct(object $config)
    {
        parent::__construct();
        $this->mid = $config->logic_mid;
        $this->user = $config->logic_user;
        $this->password = $config->logic_secret;
        $this->baseUrl = env('APP_ENV') != 'production' ? $this->baseUrlTesting : $this->baseUrlProd;
        $encoded = base64_encode($this->user . ":" . $this->password);
        $this->setHeaders([
            'Authorization' => 'Basic ' . $encoded,
            'Content-Type'  => 'application/json'
        ]);
    }

    /**
     * Basic Test
     * @return mixed
     * @throws GuzzleException
     * @throws LogicException
     */
    public function test() : mixed
    {
        return $this->send($this->baseUrl . "rest/", 'put', [
            'merchid' => $this->mid
        ]);
    }

    /**
     * Attempt to Authorize a given token.
     * @param string      $token
     * @param string      $name
     * @param string|null $expiry
     * @param string|null $cvv
     * @param string|null $postal
     * @return mixed
     * @throws GuzzleException
     * @throws LogicException
     */
    public function preauth(string $token, string $name, ?string $expiry = null, ?string $cvv = null, string $postal = null): object
    {
        $amount = 0;
        $data = [
            'merchid'      => $this->mid,
            'amount'       => $amount,
            'account'      => $token,
            'name'         => $name,
            'cof'          => 'M',
            'cofscheduled' => 'N',
            'ecomind'      => 'E'
        ];
        if ($cvv)
        {
            $data['cvv2'] = $cvv;
        }
        if ($postal)
        {
            $data['postal'] = $postal;
        }
        if ($expiry)
        {
            $data['expiry'] = $expiry;
        }
        return $this->send($this->baseUrl . "rest/auth", 'post', $data);
    }

    /**
     * Attempt to Capture/Process a Payment
     * @param string      $token
     * @param int         $amount
     * @param string      $name
     * @param string|null $expiry
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function authorize(string $token, int $amount, string $name, ?string $expiry): object
    {
        $data = [
            'merchid'      => $this->mid,
            'amount'       => $amount,
            'account'      => $token,
            'name'         => $name,
            'capture'      => 'y',
            'ecomind'      => 'R',
            'cof'          => 'M',
            'cofscheduled' => 'Y'
        ];
        if ($expiry)
        {
            $data['expiry'] = $expiry;
        }
        // Send on preauth and auth
        // E - Customer initiated , R - Recurring automated
        // cofscheduled = Y if recurring, N if not
        return $this->send($this->baseUrl . "rest/auth", 'post', $data);
    }

    /**
     * One-time Customer Initiated Authorization
     * @param string      $token
     * @param int         $amount
     * @param string      $name
     * @param string|null $cvv
     * @param string|null $postal
     * @param string|null $expiry
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function authorizeInstance(
        string $token,
        int $amount,
        string $name,
        ?string $cvv = null,
        ?string $postal = null,
        ?string $expiry = null
    ): object {

        $data = [
            'merchid'      => $this->mid,
            'amount'       => $amount,
            'account'      => $token,
            'name'         => $name,
            'capture'      => 'y',
            'ecomind'      => 'E',
            'cof'          => 'C',
            'cofscheduled' => 'N'
        ];
        if ($cvv)
        {
            $data['cvv2'] = $cvv;
        }
        if ($postal)
        {
            $data['postal'] = $postal;
        }
        if ($expiry)
        {
            $data['expiry'] = $expiry;
        }
        // Send on preauth and auth
        // E - Customer initiated , R - Recurring automated
        // cofscheduled = Y if recurring, N if not
        return $this->send($this->baseUrl . "rest/auth", 'post', $data);
    }

    /**
     * Process via ACH
     * @param string $aba
     * @param string $account
     * @param int    $amount
     * @param string $name
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function processACH(string $aba, string $account, int $amount, string $name): object
    {
        $data = [
            'merchid'  => $this->mid,
            'amount'   => $amount,
            'account'  => $account,
            'bankaba'  => $aba,
            'name'     => $name,
            'capture'  => 'y',
            'ecomind'  => 'E', // Set as webpayment
            'accttype' => 'ECHK' // Set as checking
        ];
        return $this->send($this->baseUrl . "rest/auth", 'post', $data);
    }


}
