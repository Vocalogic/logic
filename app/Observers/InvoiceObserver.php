<?php

namespace App\Observers;

use App\Enums\Core\IntegrationType;
use App\Models\BillItem;
use App\Models\Invoice;
use App\Operations\Integrations\Accounting\Finance;

class InvoiceObserver
{

    static public bool $running = false;    // Prevents nested observer calls.


    /**
     * Sync new invoice
     * @param Invoice $invoice
     * @return void
     */
    public function created(Invoice $invoice) : void
    {
        $invoice->update(['previous_balance' => $invoice->account->account_balance]);
        if ($invoice->total < 0) return;
        if (self::$running) return;
        self::$running = true;

        // Update Finance 3rd Party
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::syncInvoice($invoice);
        }

        self::$running = false;
    }

    /**
     * Update Invoice
     * @param Invoice $invoice
     * @return void
     */
    public function updated(Invoice $invoice): void
    {
        if ($invoice->total < 0) return;
        if (self::$running) return;
        self::$running = true;

        // Update Finance 3rd Party
        if (hasIntegration(IntegrationType::Finance))
        {
            try
            {
                Finance::syncInvoice($invoice);

            } catch (\Exception $e)
            {
               info("Failed to Update Invoice $invoice->id with Finance Module: " . $e->getMessage());
            }
        }
        self::$running = false;
    }

    /**
     * Remove an invoice
     * @param Invoice $invoice
     * @return void
     */
    public function deleted(Invoice $invoice): void
    {
        if ($invoice->total < 0) return;
        if (self::$running) return;
        self::$running = true;

        // Update Finance 3rd Party
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::deleteInvoice($invoice);
        }
        self::$running = false;
    }

}
