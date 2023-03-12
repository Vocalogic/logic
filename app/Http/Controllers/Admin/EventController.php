<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\ActivityType;
use App\Enums\Core\InvoiceStatus;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountItem;
use App\Models\Activity;
use App\Models\HardwareOrder;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Provisioning;
use App\Models\Shipment;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class EventController extends Controller
{

    /**
     * Get all events for dashboard
     * @return array
     */
    public function all(): array
    {
        $events = [];
        foreach (Activity::whereNotNull('event')->get() as $event)
        {
            if ($event->type == ActivityType::Lead)
            {
                $color = $event->lead->agent ? $event->lead->agent->color : "#dedede";
                $events[] = (object)[
                    'title' => $event->post,
                    'start' => $event->event->format("Y-m-d H:i"),
                    'url'   => sprintf("%s/admin/leads/%d", setting('brand.url'), $event->refid),
                    'popup' => (object)[
                        'title'  => "(LEAD) " . Lead::find($event->refid)->company,
                        'descri' => $event->post
                    ],
                    'color' => $color
                ];
            }

            if ($event->type == ActivityType::Account)
            {

                $events[] = (object)[
                    'title' => $event->post,
                    'start' => $event->event->format("Y-m-d H:i"),
                    'url'   => sprintf("%s/admin/accounts/%d", setting('brand.url'), $event->refid),
                    'color' => "#a1b2e3",
                    'popup' => (object)[
                        'title'  => Account::find($event->refid)->name,
                        'descri' => $event->post
                    ],
                ];
            }
        }

        // Get Shipment Arrivals
        foreach (Shipment::where('active', true)->whereNotNull('expected_arrival')->get() as $hw)
        {
            $events[] = (object)[
                'title' => "SHIP: {$hw->order->account->name}",
                'start' => $hw->expected_arrival->format("Y-m-d"),
                'url'   => sprintf("%s/admin/orders/%d", setting('brand.url'), $hw->order->id),
                'color' => "#aabbcc",
                'popup' => (object)[
                    'title'  => $hw->order->account->name,
                    'descri' => "Hardware Order #$hw->id is scheduled to arrive at $hw->ship_address."
                ],
            ];
        }

        // Suspension of Services
        $list = [];
        foreach(AccountItem::whereNotNull('suspend_on')->get() as $item)
        {
            $list[] = $item->item->name;
        }
        if (!empty($list))
        {
            $events[] = (object)[
                'title' => "SUSPENSION: " . $item->account->name,
                'start' => $item->suspend_on->format("Y-m-d"),
                'url'   => sprintf("%s/admin/accounts/%d", setting('brand.url'), $item->account->id),
                'color' => "#DACE53",
                'popup' => (object)[
                    'title'  => $item->account->name,
                    'descri' => "Suspend Services: " . implode(", ", $list)
                ],
            ];
        }

        $list = [];
        foreach(AccountItem::whereNotNull('terminate_on')->get() as $item)
        {
            $list[] = $item->item->name;
        }
        if (!empty($list) && isset($item))
        {
            $events[] = (object)[
                'title' => "TERMINATION: " . $item->account->name,
                'start' => $item->terminate_on->format("Y-m-d"),
                'url'   => sprintf("%s/admin/accounts/%d", setting('brand.url'), $item->account->id),
                'color' => "#DA302C",
                'popup' => (object)[
                    'title'  => $item->account->name,
                    'descri' => "Terminate Services: " . implode(", ", $list)
                ],
            ];
        }
        // Termination of services


        return $events;
    }


    /**
     * Show event for editing
     * @param Activity $event
     * @return View
     */
    public function show(Activity $event): View
    {
        return view('admin.events.show')->with('event', $event);
    }

    /**
     * Get events for an account
     * @param Account $account
     * @return array
     */
    public function getAccount(Account $account): array
    {
        $events = [];
        foreach (Activity::whereNotNull('event')->where('refid', $account->id)->where('type', 'ACCOUNT')
                     ->get() as $event)
        {
            $events[] = (object)[
                'title' => $event->post,
                'start' => $event->event->format("Y-m-d H:i"),
                'url'   => sprintf("%s/admin/accounts/%d", setting('brand.url'), $event->refid),
                'color' => "#a1b2e3"
            ];
        }

        // Get Hardware Arrival Events
        foreach (Shipment::with(['order'])->where('active', true)->whereNotNull('expected_arrival')->get() as $hw)
        {
            if ($hw->order->account->id == $account->id)
            {
                $events[] = (object)[
                    'title' => "SHIP: {$hw->order->account->name}",
                    'start' => $hw->expected_arrival->format("Y-m-d"),
                    'url'   => sprintf("%s/admin/accounts/%d/orders/%d", setting('brand.url'), $hw->order->account->id,
                        $hw->order->id),
                    'color' => "#aabbcc",
                    'popup' => (object)[
                        'title'  => $hw->order->account->name,
                        'descri' => "Hardware Order #$hw->id is scheduled to arrive at location."
                    ],
                ];
            }
        }
        return $events;
    }

    /**
     * Show lead events.
     * @param Lead $lead
     * @return array
     */
    public function getLead(Lead $lead): array
    {
        $events = [];
        foreach (Activity::whereNotNull('event')->where('refid', $lead->id)->where('type', 'LEAD')
                     ->get() as $event)
        {
            $events[] = (object)[
                'title' => $event->post,
                'start' => $event->event->format("Y-m-d H:i"),
                'url'   => sprintf("%s/admin/leads/%d", setting('brand.url'), $event->refid),
                'color' => "#a1b2e3"
            ];
        }
        return $events;
    }

    /**
     * Get calendar for invoices for invoice page.
     * @return array
     */
    public function invoices(): array
    {
        $events = [];
        // #241 - Get Invoices and Projected Recurring Invoices
        foreach(Invoice::with(['items', 'account'])
                    ->whereIn('status', [InvoiceStatus::SENT, InvoiceStatus::PARTIAL])
                    ->get() as $invoice)
        {
            $events[] = (object)[
                'title' => "Invoice #{$invoice->id} due",
                'start' => $invoice->due_on->format("Y-m-d"),
                'url'   => sprintf("%s/admin/invoices/%d", setting('brand.url'), $invoice->id),
                'color' => "#3FB84A",
                'popup' => (object)[
                    'title'  => $invoice->account->name,
                    'descri' => "Total: $" . moneyFormat($invoice->total) . "<br/>Balance: $" . moneyFormat($invoice->balance)
                ],
            ];
        }

        foreach(Account::with(['items'])->whereNotNull('next_bill')->get() as $account)
        {
            $events[] = (object)[
                'title' => "Recurring Invoice ($". moneyFormat($account->mrr).")",
                'start' => $account->next_bill->format("Y-m-d"),
                'url'   => sprintf("%s/admin/accounts/%d", setting('brand.url'), $account->id),
                'color' => "#6590FF",
                'popup' => (object)[
                    'title'  => $account->name,
                    'descri' => "New Recurring Invoice for $".moneyFormat($account->mrr) . " will be generated."
                ],
            ];
        }
        return $events;
    }

    /**
     * Update an event.
     * @param Activity $event
     * @param Request  $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function update(Activity $event, Request $request): RedirectResponse
    {
        $request->validate(['post' => 'required', 'event' => 'required']);
        try
        {
            $parse = Carbon::parse($request->event);
        } catch (InvalidFormatException $e)
        {
            throw new LogicException("Invalid Time. Specify Time in MM/DD/YY HH:MM format (i.e. 01/01/22 16:00)");
        }
        $event->update(['post' => $request->get('post'), 'event' => $parse]);
        return redirect()->back()->with('message', "Event updated successfully.");
    }

}
