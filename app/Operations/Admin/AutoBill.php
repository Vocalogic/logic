<?php

namespace App\Operations\Admin;

use App\Enums\Core\InvoiceStatus;
use App\Models\Invoice;
use Exception;

/**
 * The Autobill class will be used to determine if outstanding invoices should be
 * automatically attempted to be processed and self-resolve.
 */
class AutoBill
{
    /**
     * This method will be called from the scheduler.
     * @return void
     */
    static public function run(): void
    {
        $x = new self;
        info("Starting Auto-Bill Engine..");
        $x->init();
    }

    /**
     * Main Routine to run through open invoices.
     * @return void
     */
    public function init(): void
    {
        foreach (Invoice::whereIn('status', [InvoiceStatus::PARTIAL->value, InvoiceStatus::SENT->value])
                     ->get() as $invoice)
        {
            if (!$invoice->balance) // Zero $ balance. This should be set to paid.
            {
                info("Invoice #$invoice->id has a zero dollar balance, setting to paid..");
                $invoice->update(['status' => InvoiceStatus::PAID, 'paid_on' => now()]);
                continue;
            }

            // Is this invoice due to be paid?
            if (!$invoice->due_on) continue;                                     // Why would this not be set?
            if ($invoice->due_on->startOfDay() > now()->startOfDay()) continue; // Not due yet.

            // We got a live one! Check autobill for default recurring and does not have recurring profile assigned.
            if (!$invoice->account->auto_bill && !$invoice->recurringProfile)
            {
                info("Invoice #$invoice->id is past/due but auto-bill is not enabled. Skipping..");
                continue;
            }
            if ($invoice->account->declined)
            {
                info("Invoice #$invoice->id should be processed, but the account is in a declined state. Skipping..");
                continue;
            }

            if (!$invoice->account->merchant_payment_token && !$invoice->account->merchant_ach_account)
            {
                info("Invoice #$invoice->id should be processed, but no payment token or account on file. Skipping..");
                continue;
            }

            if (!$invoice->account->payment_method) // No method selected at all
            {
                info("Invoice #$invoice->id should be processed but no payment method was selected for the account!");
                continue;
            }

            if (!$invoice->account->payment_method->canAutoBill()) // Method can't be auto processed.
            {
                info("Invoice #$invoice->id should be processed but the payment method selected has no auto-billing capabilities.");
                continue;
            }
            if ($invoice->recurringProfile && !$invoice->recurringProfile->auto_bill)
            {
                info("Invoice #$invoice->id has recurring profile and is set to not auto-bill.");
                continue;
            }

            // Not declined, auto bill is set, has a balance, is due, has a payment method and can be auto'd.. Here we go
            // Remember.. ANY exception to this should IMMEDIATELY put the account in a declined state.
            // We're not going back to 2015 vocalogic where we billed a customer 10 times because of an error.
            info("Invoice #{$invoice->id} is attempting to be processed automatically..");
            try
            {
                $now = now()->format("F d, Y");
                $invoice->processPayment(
                    $invoice->account->payment_method,
                    $invoice->balance,
                    "Auto-processed on $now");
                info("Processing Completed Successfully");
            } catch (Exception $e)
            {
                info("Attempted to process #$invoice->id but transaction failed. Account set in declined state. Email sent. Reason: " . $e->getMessage());
            }
        }
    }

}
