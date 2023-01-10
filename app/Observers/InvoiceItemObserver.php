<?php

namespace App\Observers;

use App\Enums\Core\IntegrationType;
use App\Enums\Core\InvoiceStatus;
use App\Models\InvoiceItem;
use App\Operations\Integrations\Accounting\Finance;

class InvoiceItemObserver
{
    static public bool $running = false;    // Prevents nested observer calls.

    /**
     * Sync invoice item
     * @param InvoiceItem $item
     * @return void
     */
    public function created(InvoiceItem $item) : void
    {
        if ($item->invoice->status != InvoiceStatus::SENT) return; // do nothing for unsent
        if (self::$running) return;
        self::$running = true;

        // Update Finance 3rd Party
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::syncInvoice($item->invoice);
        }

        self::$running = false;
    }

    /**
     * Update Invoice Item
     * @param InvoiceItem $item
     * @return void
     */
    public function updated(InvoiceItem $item): void
    {
        if ($item->invoice->status != InvoiceStatus::SENT) return; // do nothing for unsent
        if (self::$running) return;
        self::$running = true;

        // Update Finance 3rd Party
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::syncInvoice($item->invoice);
        }
        self::$running = false;
    }

    /**
     * Remove an invoice
     * @param InvoiceItem $item
     * @return void
     */
    public function deleted(InvoiceItem $item): void
    {
        if (self::$running) return;
        self::$running = true;

        // Update Finance 3rd Party
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::syncInvoice($item->invoice);
        }
        self::$running = false;
    }

}
