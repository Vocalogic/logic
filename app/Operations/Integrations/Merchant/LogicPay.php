<?php

namespace App\Operations\Integrations\Merchant;

use App\Enums\Core\IntegrationRegistry;
use App\Exceptions\LogicException;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Operations\API\LogicPay\LPCore;
use App\Operations\Integrations\BaseIntegration;
use App\Operations\Integrations\Integration;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;

class LogicPay extends BaseIntegration implements Integration, MerchantInterface
{
    public IntegrationRegistry $ident = IntegrationRegistry::LogicPay;
    public LPCore              $lp;

    /**
     * Bind our API
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return name
     * @return string
     */
    public function getName(): string
    {
        return "LogicPay";
    }

    public function getWebsite(): string
    {
        return "https://www.vocalogic.com/logicpay";
    }

    public function getDescription(): string
    {
        return "Get ACH and competitive credit card rate capabilities with LogicPay. LogicPay is included by default
        with all Logic installations and can be activated after successful merchant registration (approx 24 hours).";
    }

    public function getLogo(): string
    {
        return "https://my.vocalogic.com/file/627c11471015b.png";
    }

    /**
     * Get required configuration
     * @return array
     */
    public function getRequired(): array
    {
        return [
            (object)[
                'var'         => 'logic_mid',
                'item'        => "LogicPay Merchant ID:",
                'description' => "Enter your LogicPay Merchant ID",
                'default'     => '',
                'protected'   => false,
            ],

            (object)[
                'var'         => 'logic_user',
                'item'        => "LogicPay User ID:",
                'description' => "Enter your LogicPay Merchant User ID",
                'default'     => '',
                'protected'   => false,
            ],

            (object)[
                'var'         => 'logic_secret',
                'item'        => "Logic Secret Key/Password:",
                'description' => "Enter the API Password for LogicPay",
                'default'     => '',
                'protected'   => true,
            ],
            (object)[
                'var'         => 'logic_ach',
                'item'        => "Enable ACH? (Y/N):",
                'description' => "If you wish to enable ACH Processing enter Y",
                'default'     => 'Y',
                'protected'   => false,
            ],
        ];
    }

    /** --------------- End of Configuration -------------- */

    public function findCustomerBy(string $method = 'email', string $query = ''): mixed
    {
        return 'N/A';
    }

    public function syncCustomer(Account $account): void
    {

    }

    /**
     * Attempt to authorize payment for an invoice
     * @param Invoice $invoice
     * @param int     $amount
     * @return string
     * @throws GuzzleException
     * @throws LogicException
     */
    public function authorize(Invoice $invoice, int $amount): string
    {
        $lp = new LPCore($this->config);
        //   $result = $lp->authorizeInstance($invoice->account->merchant_payment_token, $amount, $invoice->account->name, '341', '30005', "1025");
        $meta = (object) $invoice->account->merchant_metadata;
        $expiry = (isset($meta->expiration) && $meta->expiration) ? $meta->expiration : null;
        $result = $lp->authorize($invoice->account->merchant_payment_token, $amount, $invoice->account->name, $expiry);
        if ($result->respstat == 'A')
        {
            $invoice->account->update(['merchant_payment_token' => $result->token]); // Refresh with new token
            return $result->authcode;
        }
        else
        {
            $text = $result->resptext ?? null;
            throw new LogicException("Payment Declined (" . $text . ")");
        }
    }

    /**
     * Add payment using the account and the request data coming back
     * from CoPilot.
     * @param Account $account
     * @param string  $token
     * @return mixed
     * @throws GuzzleException
     * @throws LogicException
     */
    public function addPaymentMethod(Account $account, string $token): mixed
    {
        $lp = new LPCore($this->config);
        $result = $lp->preauth($token, $account->name);
        if ($result->respstat != 'A')
        {
            throw new LogicException("Authorization Declined.");
        }
        $last4 = substr($token, -4);
        $type = match ($token[1])
        {
            '3' => "American Express",
            '4' => "Visa",
            '5' => "Mastercard",
            '6' => "Discover",
            default => "Unknown"
        };
        $account->update([
            'merchant_payment_token' => $token,
            'merchant_payment_last4' => $last4,
            'merchant_payment_type'  => $type
        ]);
        return $result;
    }


    /**
     * Transaction Fees are not billed per transaction with LP
     * @param Transaction $transaction
     * @return float
     */
    public function syncFee(Transaction $transaction): float
    {
        return 0.00;
    }

    /**
     * Is ACH Enabled?
     * @return bool
     */
    public function achEnabled(): bool
    {
        $i = \App\Models\Integration::where('ident', 'logic')->first();
        $unp = $i->unpacked;
        if ($unp->logic_ach == 'Y') return true;
        return false;
    }

    /**
     * Attempt to process an invoice using ACH
     * @param Invoice $invoice
     * @param int     $amount
     * @return string
     * @throws GuzzleException
     * @throws LogicException
     */
    public function ach(Invoice $invoice, int $amount): string
    {
        $lp = new LPCore($this->config);
        $result = $lp->processACH($invoice->account->merchant_ach_aba, $invoice->account->merchant_ach_account, $amount,
            $invoice->account->name);
        if ($result->respstat == 'A')
        {
            $invoice->account->update(['merchant_payment_token' => $result->token]); // Refresh with new token
            return $result->authcode;
        }
        else
        {
            throw new LogicException("Payment Declined");
        }

    }

}
