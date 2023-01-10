<?php

namespace App\Observers;

use App\Enums\Core\IntegrationType;
use App\Models\Account;
use App\Operations\Integrations\Accounting\Finance;
use App\Operations\Integrations\Merchant\Merchant;
use App\Operations\Integrations\Support\Support;

class AccountObserver
{
    static public bool $running = false;    // Prevents nested observer calls.

    /**
     * Executed when an account is created
     * @param Account $account
     * @return void
     */
    public function created(Account $account) : void
    {
        if (self::$running) return;
        self::$running = true;
        // Update Merchant Provider
        if(hasIntegration(IntegrationType::Merchant))
        {
            Merchant::syncAccount($account);
        }

        // Update Support Systems
        if(hasIntegration(IntegrationType::Support))
        {
            Support::syncAccount($account);
        }

        // Update Finance 3rd Party
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::syncAccount($account);
        }

        self::$running = false;
    }

    /**
     * Update merchant account when updated.
     * @param Account $account
     * @return void
     */
    public function updated(Account $account): void
    {
        if (self::$running) return;
        self::$running = true;
        // Update Merchant Provider
        if(hasIntegration(IntegrationType::Merchant))
        {
            Merchant::syncAccount($account);
        }

        // Update Support Systems
        if(hasIntegration(IntegrationType::Support))
        {
            Support::syncAccount($account);
        }

        // Update finance
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::syncAccount($account);
        }

        self::$running = false;
    }

}
