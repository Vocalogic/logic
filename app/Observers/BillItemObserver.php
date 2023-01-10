<?php

namespace App\Observers;

use App\Enums\Core\IntegrationType;
use App\Models\BillCategory;
use App\Models\BillItem;
use App\Operations\Integrations\Accounting\Finance;

class BillItemObserver
{

    static public bool $running = false;    // Prevents nested observer calls.

    /**
     * Create new billitem
     * @param BillItem $item
     * @return void
     */
    public function created(BillItem $item) : void
    {
        if (self::$running) return;
        self::$running = true;

        // Update Finance 3rd Party
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::syncItem($item);
        }
        self::$running = false;
    }

    /**
     * Update Bill Item
     * @param BillItem $item
     * @return void
     */
    public function updated(BillItem $item): void
    {
        if (self::$running) return;
        self::$running = true;

        // Update Finance 3rd Party
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::syncItem($item);
        }
        self::$running = false;
    }

}
