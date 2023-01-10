<?php

namespace App\Observers;

use App\Enums\Core\IntegrationType;
use App\Models\Transaction;
use App\Operations\Integrations\Accounting\Finance;

class TransactionObserver
{
    static public bool $running = false;    // Prevents nested observer calls.

    /**
     * Sync new transaction
     * @param Transaction $transaction
     * @return void
     */
    public function created(Transaction $transaction) : void
    {
        if (self::$running) return;
        self::$running = true;

        // Update Finance 3rd Party
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::syncTransaction($transaction);
        }
        self::$running = false;
    }

    // Transactions are not really updated. They are just posted.

}
