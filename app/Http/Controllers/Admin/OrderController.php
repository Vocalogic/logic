<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class OrderController extends Controller
{
    /**
     * Show open orders
     * @return View
     */
    public function index(): View
    {
        return view('admin.orders.index');
    }

    /**
     * Show order
     * @param Order $order
     * @return View
     */
    public function show(Order $order): View
    {
        return view('admin.orders.show')->with('order', $order);
    }

    /**
     * Close order
     * @param Order $order
     * @return string[]
     */
    public function close(Order $order): array
    {
        $order->update(['completed_on' => now(), 'active' => false]);
        return ['callback' => "redirect:/admin/accounts/{$order->account->id}"];
    }

    /**
     * Send order to customer
     * @param Order $order
     * @return string[]
     */
    public function send(Order $order): array
    {
        $order->send();
        return ['callback' => 'reload'];
    }

    /**
     * Show item assignment form
     * @param Order     $order
     * @param OrderItem $item
     * @return View
     */
    public function assignForm(Order $order, OrderItem $item): View
    {
        return view('admin.orders.assign')->with([
            'order' => $order,
            'item'  => $item
        ]);
    }

    /**
     * Update the order assignment.
     * @param Order     $order
     * @param OrderItem $item
     * @param Request   $request
     * @return RedirectResponse
     */
    public function assign(Order $order, OrderItem $item, Request $request): RedirectResponse
    {
        $item->update(['assigned_id' => $request->assigned_id]);
        if ($item->status == 'Incomplete' && $request->assigned_id)
        {
            $item->update(['status' => "Assigned"]);
        }
        return redirect()->back()->with('message', "Assignment Updated.");
    }

    /**
     * Show notes modal
     * @param Order     $order
     * @param OrderItem $item
     * @return View
     */
    public function noteForm(Order $order, OrderItem $item): View
    {
        return view('admin.orders.notes')->with(['order' => $order, 'item' => $item]);
    }

    /**
     * Apply note to order item.
     * @param Order     $order
     * @param OrderItem $item
     * @param Request   $request
     * @return RedirectResponse
     */
    public function addNote(Order $order, OrderItem $item, Request $request): RedirectResponse
    {
        $request->validate(['note' => "required"]);
        $item->notes()->create([
            'note'     => $request->note,
            'user_id'  => user()->id,
            'order_id' => $order->id
        ]);
        return redirect()->back();
    }

    /**
     * Complete an item
     * @param Order     $order
     * @param OrderItem $item
     * @return string[]
     */
    public function completeItem(Order $order, OrderItem $item): array
    {
        $item->notes()->create([
            'note'     => "Item Marked Completed",
            'user_id'  => user()->id,
            'order_id' => $order->id,
        ]);
        $item->update(['status' => 'Complete']);
        return ['callback' => 'reload'];
    }

    /**
     * Show shipment modal
     * @param Order     $order
     * @param OrderItem $item
     * @return View
     */
    public function shipModal(Order $order, OrderItem $item): View
    {
        return view('admin.orders.shipment')->with(['order' => $order, 'item' => $item]);
    }

    /**
     * Set new or assign new shipment.
     * @param Order     $order
     * @param OrderItem $item
     * @param Request   $request
     * @return RedirectResponse
     */
    public function setShipment(Order $order, OrderItem $item, Request $request): RedirectResponse
    {
        if ($request->shipment_id) // Reassigning Order
        {
            $item->update(['shipment_id' => $request->shipment_id]);
            return redirect()->back();
        }
        $request->validate([
            'vendor_id'    => 'numeric|required',
            'ship_company' => 'required',
            'ship_contact' => 'required',
            'ship_address' => 'required',
            'ship_csz'     => 'required'
        ]);
        $shipment = (new Shipment)->create([
            'order_id'      => $order->id,
            'vendor_id'     => $request->vendor_id,
            'active'        => true,
            'status'        => 'Draft',
            'ship_contact'  => $request->ship_contact,
            'ship_company'  => $request->ship_company,
            'ship_address'  => $request->ship_address,
            'ship_address2' => $request->ship_address2,
            'ship_csz'      => $request->ship_csz
        ]);
        $item->update(['shipment_id' => $shipment->id]);
        return redirect()->back();
    }

    /**
     * Verify order and log.
     * @param Order $order
     * @return string[]
     */
    public function verify(Order $order): array
    {
        $order->logs()->create([
            'status'  => OrderStatus::Verified,
            'note'    => "Verified by " . user()->short,
            'user_id' => user()->id
        ]);
        // Change order status
        $order->update(['status' => OrderStatus::Verified]);
        return ['callback' => 'reload'];
    }

    /**
     * Set order to in progress
     * @param Order $order
     * @return string[]
     */
    public function progress(Order $order): array
    {
        $order->logs()->create([
            'status'  => OrderStatus::InProgress,
            'note'    => "Order being fulfilled by " . user()->short,
            'user_id' => user()->id
        ]);
        // Change order status
        $order->update(['status' => OrderStatus::InProgress]);
        return ['callback' => 'reload'];
    }


}
