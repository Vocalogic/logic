<?php

namespace App\Operations\Integrations\Merchant;

use App\Enums\Core\IntegrationRegistry;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Operations\API\Stripe\StripeCore;
use App\Operations\Integrations\BaseIntegration;
use App\Operations\Integrations\Integration;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;

class Stripe extends BaseIntegration implements Integration, MerchantInterface
{

    public IntegrationRegistry $ident = IntegrationRegistry::Stripe;
    public StripeCore          $iStripe;

    /**
     * Bind our API
     */
    public function __construct()
    {
        parent::__construct();
        $this->iStripe = new StripeCore($this->config->stripe_secret);
    }

    /**
     * Return name
     * @return string
     */
    public function getName(): string
    {
        return "Stripe";
    }

    public function getWebsite(): string
    {
        return "https://www.stripe.com";
    }

    public function getDescription(): string
    {
        return "Millions of companies of all sizes—from startups to Fortune 500s—use Stripe’s software and APIs to
         accept payments, send payouts, and manage their businesses online.";
    }

    public function getLogo(): string
    {
        return "https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Stripe_Logo%2C_revised_2016.svg/2560px-Stripe_Logo%2C_revised_2016.svg.png";
    }

    /**
     * Get required configuration
     * @return array
     */
    public function getRequired(): array
    {
        return [
            (object)[
                'var'         => 'stripe_secret',
                'item'        => "Stripe Secret Key:",
                'description' => "Enter the stripe secret",
                'default'     => '',
                'protected'   => true,
            ],
            (object)[
                'var'         => 'stripe_publish',
                'item'        => "Stripe Publish Key:",
                'description' => "Enter the stripe published key",
                'default'     => '',
                'protected'   => false,
            ],
        ];
    }

    /** --------------- End of Configuration -------------- */

    /**
     * Find a customer by email, name, etc.
     * @param string $method
     * @param string $query
     * @return mixed
     * @throws ApiErrorException
     */
    public function findCustomerBy(string $method = 'email', string $query = ''): mixed
    {
        return $this->iStripe->findCustomerBy($method, $query);
    }

    /**
     * Create a new Customer with Stripe API
     * @param Account $account
     * @return mixed
     * @throws ApiErrorException
     */
    public function createCustomer(Account $account): mixed
    {
        $desc = sprintf("Logic Account #%d", $account->id);
        $address = [
            'city'        => $account->city,
            'country'     => $account->country,
            'line1'       => $account->address,
            'line2'       => $account->address2,
            'postal_code' => $account->postcode,
            'state'       => $account->state
        ];
        return $this->iStripe->createCustomer($account->name, $desc, $account->admin->email, $address);
    }

    /**
     * Sync customer record.
     * @param Account $account
     * @return void
     * @throws ApiErrorException
     */
    public function syncCustomer(Account $account): void
    {
        $address = [
            'city'        => $account->city,
            'country'     => $account->country,
            'line1'       => $account->address,
            'line2'       => $account->address2,
            'postal_code' => $account->postcode,
            'state'       => $account->state
        ];
        $desc = sprintf("Logic Account #%d", $account->id);
        if ($account->merchant_account_id)
        {
            $this->iStripe->updateCustomer($account->merchant_account_id, $account->name, $desc, $account->admin->email,
                $address);
        }
        else
        {
            $account->update(['merchant_account_id' => $this->createCustomer($account)]);
        }
    }

    /**
     * Create a payment intent. This will be used to create a new cc token
     * @param int $amount
     * @return mixed
     * @throws ApiErrorException
     */
    public function getPaymentIntent(int $amount): mixed
    {
        return $this->iStripe->getPaymentIntent($amount);
    }

    /**
     * Add payment using the account and the request data coming back
     * from stripe.
     * @param Account $account
     * @param Request $request
     * @return void
     * @throws ApiErrorException
     */
    public function addPaymentMethod(Account $account, Request $request): void
    {
        if (!$account->merchant_account_id)
        {
            // Create Customer First.
            $this->syncCustomer($account);
            $account->refresh();
        }
        $token = $this->iStripe->addPaymentMethod(
            $account->merchant_account_id,
            $request->stripeToken
        );
        $account->update([
            'merchant_payment_token' => $token->id,
            'merchant_payment_type'  => $token->brand,
            'merchant_payment_last4' => $token->last4
        ]);
    }

    /**
     * Authorize a transaction using Stripe.
     * @param Invoice $invoice
     * @param int     $amount
     * @return string
     * @throws ApiErrorException
     */
    public function authorize(Invoice $invoice, int $amount): string
    {
        $trans = $this->iStripe->authorize($amount, $invoice->account->merchant_account_id,
            $invoice->account->merchant_payment_token, "Payment for Invoice #{$invoice->id}");
        return $trans->id;
    }

    /**
     * Attempt to get the fee associated to a transaction
     * @param Transaction $transaction
     * @return float
     * @throws ApiErrorException
     */
    public function syncFee(Transaction $transaction): float
    {
        if (!$transaction->remote_transaction_id) return 0;
        return $this->iStripe->getFee($transaction->remote_transaction_id);
    }
}
