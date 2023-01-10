<?php

namespace App\Operations\Integrations\Merchant;

use App\Models\Account;

interface MerchantInterface
{

    /**
     * Lookup a customer and return the record if found.
     * @param string $method
     * @param string $query
     * @return mixed
     */
    public function findCustomerBy(string $method = 'email', string $query = ''): mixed;

    /**
     * Create a new customer with merchant and store the ID
     * to the account record. Should look up and attempt
     * to set first based on response. If found, should
     * attempt to update customer.
     * @param Account $account
     * @return void
     */
    public function syncCustomer(Account $account):void;



}
