<?php

namespace App\Operations\Core;

use App\Enums\Core\ActivityType;
use App\Enums\Core\BillFrequency;
use App\Enums\Core\InvoiceStatus;
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
                // #147 - Check Suspension and Termination Notices
                if (now()->diffInDays($invoice->due_on) > setting('invoices.terminationDays'))
                {
                    $invoice->sendTerminationNotice();
                    continue; // don't send suspension again. (or try to)
                }
                if (now()->diffInDays($invoice->due_on) > setting('invoices.suspensionDays'))
                {
                    $invoice->sendSuspensionNotice();
                }
                $lateFeeTarget = $invoice->due_on->addDays(setting('invoices.lateFeeDays'));
                if (!$invoice->account->impose_late_fee) continue; // only if this account has late fees enabled.
                if (now() >= $lateFeeTarget)
                {
                    $invoice->assessLateFee();
                }
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


    /**
     * This will generate a monthly invoice for an account.
     * @param Account $account
     * @param bool    $createOrder
     * @return void
     */
    static public function generateMonthlyInvoice(Account $account, bool $createOrder = false) : void
    {
        $account->update(['next_bill' => now()->addMonth()->setDay($account->bills_on ?? 1)]);
        if ($account->items->count() == 0) return; // Do nothing if there are no service items
        $invoice = $account->invoices()->create([
            'due_on'    => now()->addDays($account->net_terms),
            'status'    => InvoiceStatus::DRAFT,
            'recurring' => true
        ]);
        _log($invoice, "Monthly Invoice Generated");
        foreach ($account->items as $item)
        {
            if (!$item->item) continue; // Service was deleted.
            if (!$item->frequency) $item->frequency = BillFrequency::Monthly;
            if ($item->frequency != BillFrequency::Monthly)
            {
                // This isn't billed monthly; we should check next_bill. If not time yet, skip this.
                if ($item->next_bill_date && $item->next_bill_date > now()) continue;
            }
            if ($item->frequency == BillFrequency::Monthly && $item->next_bill_date)
            {
                $item->update(['next_bill_date' => null]); // ex. If someone changes from quarterly to monthly
            }
            // Next check should be if this service is temporary (such as a product that has been financed)
            $notes = $item->notes ? " ($item->notes)" : null;
            // A next bill date is assigned (if monthly it should never have that.) Just in case it got set
            // we will set Monthly to 1, so basically it won't hurt anything.
            if ($item->frequency != BillFrequency::Monthly)
            {
                // Line up new date with next invoice service day.
                $newDate = now()->addMonths($item->frequency->getMonths())->setDay($account->bills_on);
                $notes .= sprintf("Service billed %s (Next Billing Date: %s)",
                    $item->frequency->getHuman(), $newDate->format("m/d/y"));
                $item->update(['next_bill_date' => $newDate]);
            }

            if ($item->item->meta()->count())
            {
                $notes .= "<br>" . $item->iterateMeta(true);
            }
            $ii = $invoice->items()->create([
                'bill_item_id' => $item->bill_item_id,
                'code'         => $item->item->code,
                'name'         => $item->item->name,
                'description'  => $item->description . $notes,
                'price'        => $item->price,
                'qty'          => $item->qty
            ]);
            _log($ii, "Added {$item->item->name} to Invoice");

            // If addons are listed for this service item, we include them below
            if ($item->addons()->count())
            {
                foreach ($item->addons as $addon)
                {
                    $note = $addon->notes ? " - " . $addon->notes : null;
                    $ii = $invoice->items()->create([
                        'bill_item_id' => $item->bill_item_id,
                        'code'         => $item->item->code,
                        'name'         => $item->item->name . " - $addon->name",
                        'description'  => "Addon: $addon->name $note",
                        'price'        => $addon->price,
                        'qty'          => $addon->qty
                    ]);
                    _log($ii, "Added {$item->item->name} from Addon to Invoice");
                }
            }

            if ($item->remaining && $item->remaining > 0)
            {
                $newRemain = $item->remaining - 1;
                if ($newRemain <= 0)
                {
                    $item->delete();
                } // We're done with this it's paid off.
                else
                {
                    $item->update(['remaining' => $newRemain]);
                }
            }
        }
        $invoice->refresh();
        // After all this, if we have no items.. then delete ourselves.
        if ($invoice->items()->count() == 0)
        {
            $invoice->delete();
            return;
        }
        $invoice->send();
        if ($createOrder)
        {
            $invoice->createOrder();
        }
        $total = "$" . moneyFormat($invoice->total);
        _log($invoice, "Monthly Invoice Total Final: $total");
        sysact(ActivityType::Account, $account->id,
            "created monthly recurring <a href='/admin/invoices/$invoice->id'>Invoice #{$invoice->id}</a> ($total) for");
    }


}
