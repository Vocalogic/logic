<?php

namespace App\Operations\API\Stripe;

use Stripe\BalanceTransaction;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeCore
{

    /**
     * Define Client
     * @var Stripe
     */
    public Stripe $stripe;

    public string $clientSecret;

    /**
     * Build a Stripe Client with our secret.
     * @param string $clientSecret
     */
    public function __construct(string $clientSecret)
    {
        $this->clientSecret = $clientSecret;
        Stripe::setApiKey($this->clientSecret);
    }

    /**
     * Lookup a customer by name or email
     * @param string $method
     * @param string $query
     * @return string|null
     * @throws ApiErrorException
     */
    public function findCustomerBy(string $method = 'email', string $query = ''): ?string
    {
        $searchObject = [$method => $query];
        $customer = Customer::all($searchObject);
        if (!empty($customer->data) && !empty($customer->data[0]->id))
        {
            return $customer->data[0]->id;
        }
        else return null;
    }

    /**
     * Create a customer at stripe.
     * @param string $name
     * @param string $description
     * @param string $email
     * @param array  $address
     * @return string|null
     * @throws ApiErrorException
     */
    public function createCustomer(string $name, string $description, string $email, array $address): ?string
    {
        $customer = Customer::create([
            'name'        => $name,
            'description' => $description,
            'email'       => $email,
            'address'     => $address
        ]);
        return $customer->id ?? null;
    }

    /**
     * Update stripe customer.
     * @param string $token
     * @param string $name
     * @param string $description
     * @param string $email
     * @param array  $address
     * @return string|null
     * @throws ApiErrorException
     */
    public function updateCustomer(
        string $token,
        string $name,
        string $description,
        string $email,
        array $address
    ): ?string {
        $customer = Customer::update($token, [
            'name'        => $name,
            'description' => $description,
            'email'       => $email,
            'address'     => $address
        ]);
        return $customer->id ?? null;
    }

    /**
     * Get payment intent object
     * @param int $amount
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    public function getPaymentIntent(int $amount): PaymentIntent
    {
        return PaymentIntent::create(['amount' => $amount, 'currency' => 'usd']);
    }

    /**
     * Add Payment Method API Call
     * @param string $customerToken
     * @param string $token
     * @return mixed
     * @throws ApiErrorException
     */
    public function addPaymentMethod(string $customerToken, string $token): mixed
    {
        return Customer::createSource($customerToken, [
            'source' => $token
        ]);
    }

    /**
     * Authorize a card and return the charge object.
     * @param int    $amount
     * @param string $maccount
     * @param string $mtoken
     * @param string $details
     * @return Charge
     * @throws ApiErrorException
     */
    public function authorize(int $amount, string $maccount, string $mtoken, string $details): Charge
    {
        return Charge::create([
            'amount'      => $amount,
            'currency'    => 'usd',
            'source'      => $mtoken,
            'customer'    => $maccount,
            'description' => $details
        ]);
    }

    /**
     * Returns a transaction balance object that can be used to pull net proceeds.
     * @param string $balanceObject
     * @return BalanceTransaction
     * @throws ApiErrorException
     */
    public function getTransaction(string $balanceObject): BalanceTransaction
    {
        return BalanceTransaction::retrieve($balanceObject);
    }

    /**
     * Get the Fee associated with a charge.
     * @param string $chargeId
     * @return float
     * @throws ApiErrorException
     */
    public function getFee(string $chargeId): float
    {
        $charge = Charge::retrieve($chargeId);
        // Get the balance object.
        $trans = $this->getTransaction($charge->balance_transaction);
        return $trans->fee / 100;
    }


}
