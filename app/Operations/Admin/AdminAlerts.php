<?php

namespace App\Operations\Admin;

use App\Enums\Core\AlertType;
use App\Enums\Core\BillItemType;
use App\Enums\Core\InvoiceStatus;
use App\Enums\Core\ShipmentStatus;
use App\Models\Account;
use App\Models\Activity;
use App\Models\BillItem;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Shipment;
use App\Operations\Core\AlertEngine;

class AdminAlerts extends AlertEngine
{

    /**
     * Admin alert checks.
     * @return array
     */
    public function collect(): array
    {
        $alerts = [];
        // Initial Install
        $accounts = Account::with(['invoices', 'activities'])->where('active', true)->get();

        if (setting('account.2fa_method') == 'SMS' && !user()->phone)
        {
            $alerts[] = $this->instanceAlert(AlertType::Danger,
                "Two-Factor Phone Missing",
                "Logic is configured to use SMS two-factor authentication but you have no mobile number set. Please do this ASAP!",
                "Update Profile",
                "/admin/profile");
        }


        foreach ($accounts as $account)
        {
            $last = $account->activities->first();
            if (!$last) continue;
            $time = (int)setting('account.reminder');
            if (!$time) continue;
            $diff = $last->created_at->diffInDays();
            if ($diff > $time)
            {
                $alerts[] = $this->instanceAlert(AlertType::Warning,
                    "Periodic Checkin Required",
                    "$account->name has had no activity in $time days. Add a comment to clear this alert.",
                    "View $account->name",
                    "/admin/accounts/$account->id");
            }
        }

        foreach ($accounts->where('declined', true)->all() as $account)
        {
            $alerts[] = (object)[
                'type'        => AlertType::Danger,
                'title'       => "Payment Card Failure",
                'description' => "$account->name is in a payment card failure state. Try again, or get new card from customer.",
                'action'      => "Update Credit Card",
                'url'         => "/admin/accounts/$account->id?active=profile"
            ];
        }


        // Invoice Check
        $drafts = [];
        $draftHeaders = ['#', 'Account', 'Created', 'Total'];

        $invoices = Invoice::with(['items', 'account', 'transactions'])->whereIn('status', [InvoiceStatus::DRAFT])->get();
        $count = $invoices->count();
        $total = 0;
        foreach ($invoices as $invoice)
        {
            $total += $invoice->total;
            $drafts[] = [
                "<a href='/admin/invoices/$invoice->id'><span class='badge bg-primary'>#$invoice->id</span></a>",
                "<a href='/admin/accounts/{$invoice->account->id}'>{$invoice->account->name}</a>",
                $invoice->created_at->format("m/d/y"),
                "$" . moneyFormat($invoice->total)
            ];
        }
        $desc = sprintf("There are currently %d draft invoices totaling ($%s) that have not been sent to the customer.",
            $count,
            moneyFormat($total));
        if (count($drafts))
        {
            $alerts[] = $this->widgetAlert(AlertType::Info, "Draft Invoices", $count, $desc, '2187794',
                $draftHeaders, $drafts);
        }


        // Past Due Invoices
        $pastDue = [];
        $pastDueHeaders = ['#', 'Account', "Balance", "Days"];
        if (Invoice::whereIn('status', [InvoiceStatus::SENT, InvoiceStatus::PARTIAL])->count() > 0)
        {
            $invoices = Invoice::with(['items', 'account', 'transactions'])->whereIn('status', [InvoiceStatus::SENT, InvoiceStatus::PARTIAL])->get();
            $amount = 0;
            $count = 0;
            foreach ($invoices as $invoice)
            {
                if ($invoice->isPastDue)
                {
                    $amount += $invoice->balance;
                    $pastDue[] = [
                        "<a href='/admin/invoices/$invoice->id'><span class='badge bg-danger'>$invoice->id</span></a>",
                        "<a href='/admin/accounts/{$invoice->account->id}'>{$invoice->account->name}</a>",
                        "$" . moneyFormat($invoice->balance),
                        $invoice->due_on->diffInDays(now())
                    ];
                    $count++;
                }
            }
            if (count($pastDue))
            {
                $desc = "There are $count past due invoices with a total outstanding balance of $" . moneyFormat($amount) . ".";
                $alerts[] = $this->widgetAlert(AlertType::Danger, "Past Due Invoices", $count, $desc, '3188190',
                    $pastDueHeaders, $pastDue);
            }

        }
        $staleHeaders = ['Lead', "Age", "Last"];
        $stales = [];
        $count = 0;
        foreach (Lead::where('active', true)->get() as $lead)
        {
            if ($lead->partner && !$lead->partner_sourced) continue; // We can't update the lead any longer.
            if ($lead->requires_update)
            {
                $stales[] = [
                    "<a href='/admin/leads/$lead->id'>$lead->company</a>",
                    $lead->created_at->diffInDays() . " days",
                    $lead->updated_at->diffInDays() . " days"
                ];
                $count++;
            }
        }
        if (count($stales))
        {
            $desc = "There are $count stale leads that require an update.";
            $alerts[] = $this->widgetAlert(AlertType::Info, "Stale Leads", $count, $desc, '476863', $staleHeaders,
                $stales);
        }

        // Shipments still in draft.

        $drafts = [];
        $draftHeaders = ['#', 'Order', "Vendor", "Days"];
        foreach (Shipment::where('status', ShipmentStatus::Draft->value)->get() as $shipment)
        {
            $drafts[] = [
                "<a href='/admin/shipments/$shipment->id'><span class='badge bg-primary'>#$shipment->id</span></a>",
                "<a href='/admin/orders/{$shipment->order->id}'>#{$shipment->order->id}</a>",
                $shipment->vendor ? $shipment->vendor->name : "Not Set",
                $shipment->created_at->diffInDays()
            ];
        }
        if (count($drafts))
        {
            $count = count($drafts);
            $desc = "There are $count shipments that are in a draft date.";
            $alerts[] = $this->widgetAlert(AlertType::Info, "Draft Shipments", $count, $desc, '2801874', $draftHeaders,
                $drafts);
        }

        // Stale Orders
        $staleHeaders = ['Order', "Status"];
        $stales = [];
        $count = 0;
        foreach (Order::where('active', true)->get() as $order)
        {
            if ($order->requires_update)
            {
                $stales[] = [
                    "<a href='/admin/orders/$order->id'>$order->name</a>",
                    $order->status->value,
                ];
                $count++;
            }
        }
        if (count($stales))
        {
            $desc = "There are $count stale orders that require an update.";
            $alerts[] = $this->widgetAlert(AlertType::Info, "Stale Orders", $count, $desc, '3045670', $staleHeaders,
                $stales);
        }

        // Forecasted Failures
        $pastForcecast = [];
        $pastForecastHeaders = ['#', 'Name', "Forecasted"];

        foreach (Lead::where('active', true)->whereNotNull('forecast_date')->get() as $lead)
        {
            if ($lead->forecast_date <= now())
            {
                $pastForcecast[] = [
                    "<a href='/admin/leads/$lead->id'><span class='badge bg-info'>#{$lead->id}</span></a>",
                    $lead->company,
                    $lead->forecast_date->format("m/d/y")
                ];
            }
        }


        if (count($pastForcecast))
        {
            $count = sizeOf($pastForcecast);
            $desc = "There are $count forecasted leads that have not turned by the date set.";
            $alerts[] = $this->widgetAlert(AlertType::Info, "Stale Forecasts", $count, $desc, '3188190',
                $pastForecastHeaders, $pastForcecast);
        }


        return $alerts;
    }

}
