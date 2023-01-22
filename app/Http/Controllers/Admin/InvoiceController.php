<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\PaymentMethod;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\BillItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Operations\Integrations\Accounting\Finance;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class InvoiceController extends Controller
{

    /**
     * Show invoice status page
     * @return View
     */
    public function index(): View
    {
        return view('admin.invoices.index');
    }

    /**
     * Show invoice
     * @param Invoice $invoice
     * @return View
     */
    public function show(Invoice $invoice): View
    {
        if ($invoice->hasIntegrationError()) // Check to make sure we don't need to sync before showing.
        {
            Finance::syncInvoice($invoice);
        }
        return view('admin.invoices.show', ['invoice' => $invoice]);
    }


    /**
     * Add custom item to an invoice
     * @param Invoice $invoice
     * @param Request $request
     * @return RedirectResponse
     */
    public function addCustomItem(Invoice $invoice, Request $request): RedirectResponse
    {
        $request->validate(['item' => 'required', 'price' => 'required|numeric']);
        $invoice->items()->create([
            'bill_item_id' => 0,
            'code'         => '',
            'name'         => $request->item,
            'description'  => '',
            'price'        => convertMoney($request->price),
            'qty'          => $request->qty
        ]);
        return redirect()->back();
    }

    /**
     * Add registered item.
     * @param Invoice  $invoice
     * @param BillItem $item
     * @return RedirectResponse
     */
    public function addItem(Invoice $invoice, BillItem $item): RedirectResponse
    {
        $invoice->items()->create([
            'bill_item_id' => $item->id,
            'code'         => $item->code,
            'name'         => $item->name,
            'description'  => $item->description,
            'price'        => $item->nrc,
            'qty'          => 1
        ]);
        return redirect()->back();
    }

    /**
     * Remove an Item from an Invoice
     * @param Invoice     $invoice
     * @param InvoiceItem $item
     * @return string[]
     */
    public function remItem(Invoice $invoice, InvoiceItem $item): array
    {
        $item->delete();
        session()->flash('message', $item->name . " removed from Invoice");
        return ['callback' => 'reload'];
    }

    /**
     * Download Invoice
     * @param Invoice $invoice
     * @return mixed
     */
    public function download(Invoice $invoice): mixed
    {
        return $invoice->pdf();
    }

    /**
     * Send Invoice to Customer
     * @param Invoice $invoice
     * @return string[]
     */
    public function send(Invoice $invoice): array
    {
        $invoice->send();
        session()->flash('message', "Invoice Sent Successfully");
        return ['callback' => 'reload'];
    }

    /**
     * Create a new order and view order.
     * @param Invoice $invoice
     * @return string[]
     */
    public function createOrder(Invoice $invoice): array
    {
        $order = $invoice->createOrder();
        return ['callback' => "redirect:/admin/orders/$order->id"];
    }

    /**
     * Delete an invoice.
     * @param Invoice $invoice
     * @return string[]
     */
    public function destroy(Invoice $invoice): array
    {
        $account = $invoice->account;
        $invoice->items()->delete();
        $invoice->delete();
        return ['callback' => "redirect:/admin/accounts/$account->id?active=invoices"];
    }

    /**
     * Show update due date modal
     * @param Invoice $invoice
     * @return View
     */
    public function dueModal(Invoice $invoice): View
    {
        return view('admin.invoices.due_modal', ['invoice' => $invoice]);
    }

    /**
     * Update Invoice Due Date
     * @param Invoice $invoice
     * @param Request $request
     * @return RedirectResponse
     */
    public function dueUpdate(Invoice $invoice, Request $request): RedirectResponse
    {
        try
        {
            $due = Carbon::parse($request->due_on);
            $invoice->update(['due_on' => $due]);
            return redirect()->back()->with('message', "Due date updated successfully.");
        } catch (\Exception $e)
        {
            throw new \LogicException("Unable to set due date - " . $e->getMessage());
        }
        return redirect()->back();
    }

    /**
     * Authorize or Apply a payment
     * @param Invoice $invoice
     * @param Request $request
     * @return RedirectResponse
     */
    public function authPayment(Invoice $invoice, Request $request): RedirectResponse
    {
        $amount = convertMoney($request->amount); // Remove commas for amount
        if (!$request->pmethod)
        {
            session()->flash('error', "No payment method was selected. Please try again with a payment method.");
            return redirect()->back();
        }
        if ($amount <= 0)
        {
            session()->flash('error', "You must enter a positive amount for a payment amount.");
            return redirect()->back();
        }

        if ($amount > $invoice->balance)
        {
            session()->flash('error', "You cannot charge more than the balance of the invoice. ($amount)");
            return redirect()->back();
        }

        try
        {
            $method = PaymentMethod::from($request->pmethod);
            $invoice->processPayment($method, $amount, $request->details);
            $invoice->account->update(['declined' => 0]);
        } catch (LogicException $e)
        {
            session()->flash('error', "Unable to process payment: " . $e->getMessage());
            return redirect()->back();
        }
        session()->flash('message',
            "A payment of $" . moneyFormat($amount) . " was applied to Invoice #$invoice->id.");
        return redirect()->to("/admin/accounts/{$invoice->account->id}/invoices");
    }

    /**
     * Show Invoice Item Edit Modal
     * @param Invoice     $invoice
     * @param InvoiceItem $item
     * @return View
     */
    public function showItem(Invoice $invoice, InvoiceItem $item): View
    {
        return view('admin.invoices.item')->with('invoice', $invoice)->with('item', $item);
    }

    /**
     * Update an invoice item
     * @param Invoice     $invoice
     * @param InvoiceItem $item
     * @param Request     $request
     * @return RedirectResponse
     */
    public function updateInvoiceItem(Invoice $invoice, InvoiceItem $item, Request $request): RedirectResponse
    {
        $request->validate([
            'price' => 'required|numeric',
            'qty'   => 'required|numeric'
        ]);
        if (!$request->description)
        {
            $request->merge(['description' => '']);
        }
        $item->update([
            'price'       => convertMoney($request->price),
            'qty'         => $request->qty,
            'description' => $request->description
        ]);
        if (!$item->item)
        {
            $item->update(['name' => $request->name]);
        }
        return redirect()->back();
    }
}
