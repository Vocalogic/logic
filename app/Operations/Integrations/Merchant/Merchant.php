<?php

namespace App\Operations\Integrations\Merchant;

use App\Enums\Core\IntegrationRegistry;
use App\Enums\Core\IntegrationType;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;

class Merchant implements MerchantInterface
{
    public IntegrationType $type = IntegrationType::Merchant;

    /**
     * Called from Observers to sync account. Should create new merchant account
     * or update our account with the proper customer id/token
     *
     * @param Account $account
     * @return void
     */
    static public function syncAccount(Account $account): void
    {
        $x = new self();
        $x->syncCustomer($account);
    }

    /**
     * Attempt to get a fee associated to a charge
     * @param Transaction $transaction
     * @return void
     */
    public function syncFee(Transaction $transaction) : void
    {
        $transaction->update(['fee' => getIntegration($this->type)->connect()->syncFee($transaction)]);
    }

    /**
     * Find Customer by email or name, etc.
     * @param string $method
     * @param string $query
     * @return mixed
     */
    public function findCustomerBy(string $method = 'email', string $query = ''): mixed
    {
        return getIntegration($this->type)->connect()->findCustomerBy($method, $query);
    }

    /**
     * Create a customer and return the token
     * @param Account $account
     * @return string|null
     */
    public function createCustomer(Account $account): ?string
    {
        return getIntegration($this->type)->connect()->createCustomer($account);
    }


    /**
     * Sync Customer Account.
     * @param Account $account
     * @return void
     */
    public function syncCustomer(Account $account): void
    {
        if ($account->admin)
        {
            getIntegration($this->type)->connect()->syncCustomer($account);
        }
    }

    /**
     * Add payment method to account.
     * @param Account $account
     * @param Request $request
     * @return void
     */
    public function addPaymentMethod(Account $account, Request $request): void
    {
        getIntegration($this->type)->connect()->addPaymentMethod($account, $request);
    }

    /**
     * Authorize a transaction using a merchant account.
     * @param Invoice $invoice
     * @param float   $amount
     * @return string
     */
    public function authorize(Invoice $invoice, float $amount): string
    {
        return getIntegration($this->type)->connect()->authorize($invoice, $amount);
    }

    /**
     * Attempt to process a payment using ACH
     * @param Invoice $invoice
     * @param float   $amount
     * @return string
     */
    public function ach(Invoice $invoice, float $amount) : string
    {
        return getIntegration($this->type)->connect()->ach($invoice, $amount);
    }
}
