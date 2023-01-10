<?php

namespace App\Operations\Core;

use App\Enums\Core\PaymentMethod;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;

class BillingEngine
{

    /**
     * Check to see if any invoices need to be generated for an account based on its
     * next bill date.
     *
     * This is called from the daily task routine.
     */
    static public function dailyAccountInvoiceCheck(): void
    {
        foreach (Account::whereNotNull('next_bill')->where('active', true)->get() as $account)
        {
            if ($account->next_bill <= Carbon::now())
            {
                info("$account->name needs a new invoice. Starting Logic Invoice Generation Routine.. ");
                $account->generateMonthlyInvoice();
            }
        }
    }

    /**
     * Attempt to send a past due notification to accounts that have past due invoices.
     * @return void
     */
    static public function checkPastDueInvoices(): void
    {
        foreach (Invoice::whereIn('status', ['Sent', 'Partial'])->get() as $invoice)
        {
            if ($invoice->isPastDue)
            {
                info("Invoice #$invoice->id is past due. Sending to Notification routine.");
                $invoice->sendPastDueNotification(); // Method will handle checks.
            }
        }
    }

    /**
     * Sync Transaction Fees
     * @return void
     */
    static public function syncFees(): void
    {
        foreach (Transaction::where('method', PaymentMethod::CreditCard->value)->where('fee', '=', 0)->get() as $trans)
        {
            try {
                $trans->updateFee();
            } catch (Exception $e)
            {
                info("Unable to sync transaction $trans->id - ".  $e->getMessage());
            }
        }
    }


}
