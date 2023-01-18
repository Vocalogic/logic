<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
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
        return ['callback' => 'reload'];
    }

    /**
     * X-editable Update for Invoice Items
     * @param Invoice     $invoice
     * @param InvoiceItem $item
     * @param Request     $request
     * @return bool[]
     */
    public function liveUpdate(Invoice $invoice, InvoiceItem $item, Request $request): array
    {
        if ($request->name == 'price')
        {
            $request->merge(['value' => str_replace(",", '', $request->value)]);
        }
        $item->update([$request->name => $request->value]);
        return ['success' => true];
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
        return ['callback' => 'success:Invoice Sent Successfully!'];
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
        try {
            $due = Carbon::parse($request->due_on);
            $invoice->update(['due_on' => $due]);
            return redirect()->back()->with('message', "Due date updated successfully.");
        } catch(\Exception $e)
        {
            throw new \LogicException("Unable to set due date - " . $e->getMessage());
        }
        return redirect()->back();
    }
}
