<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\OrderStatus;
use App\Enums\Core\ShipmentStatus;
use App\Enums\Files\FileType;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\BillItem;
use App\Models\HardwareOrder;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Shipment;
use App\Operations\Core\LoFileHandler;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    /**
     * Show hardware orders
     * @return View
     */
    public function index(): View
    {
        return view('admin.shipments.index');
    }



    /**
     * Show Harware Order
     * @param Shipment $shipment
     * @return View
     */
    public function show(Shipment $shipment): View
    {
        return view('admin.shipments.show')->with('shipment', $shipment);
    }

    /**
     * Update shipping information.
     * @param Shipment $shipment
     * @param Request       $request
     * @return RedirectResponse
     */
    public function update(Shipment $shipment, Request $request): RedirectResponse
    {
        $request->validate([
            'vendor_id'    => 'required',
            'ship_company' => 'required',
            'ship_address' => 'required',
            'ship_csz'     => 'required',
        ]);
        $shipment->update($request->all());
        return redirect()->back()->with('message', "Shipping details updated successfully.");
    }

    /**
     * Add an item to a hardware order.
     * @param Shipment $shipment
     * @param BillItem      $item
     * @return RedirectResponse
     */
    public function addItem(Shipment $shipment, BillItem $item): RedirectResponse
    {
        $shipment->items()->create([
            'bill_item_id' => $item->id,
            'qty'          => 1
        ]);
        return redirect()->back();
    }

    /**
     * Update a hardware order item from x-edit
     * @param Shipment  $shipment
     * @param OrderItem $item
     * @param Request   $request
     * @return bool[]
     */
    public function live(Shipment $shipment, OrderItem $item, Request $request): array
    {
        $item->update([$request->name => $request->value]);
        return ['success' => true];
    }

    /**
     * Download Order PDF
     * @param Shipment $shipment
     * @return mixed
     */
    public function download(Shipment $shipment): mixed
    {
        return $shipment->pdf();
    }

    /**
     * Send order to the vendor and update our status.
     * @param Shipment $shipment
     * @return string[]
     */
    public function submit(Shipment $shipment): array
    {
        $shipment->sendToVendor();
        $shipment->update(['status' => ShipmentStatus::Submitted, 'submitted_on' => now()]);
        $shipment->order->logs()->create([
           'status' => OrderStatus::Shipped,
           'user_id' => user()->id,
           'note' => "Order Shipped (awaiting tracking)"
        ]);
        $shipment->order()->update(['status' => OrderStatus::Shipped]);
        return ['callback' => 'reload'];
    }

    /**
     * Remove an item from an order
     * @param Shipment          $shipment
     * @param OrderItem $item
     * @return RedirectResponse
     */
    public function delItem(Shipment $shipment, OrderItem $item): RedirectResponse
    {
        $item->delete();
        return redirect()->back();
    }

    /**
     * Update tracking and send to customer.
     * @param Shipment $shipment
     * @param Request       $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function updateTracking(Shipment $shipment, Request $request): RedirectResponse
    {
        if ($request->tracking && !$shipment->tracking)
        {
            // First time entering tracking
            $shipment->update(['status' => ShipmentStatus::Shipped, 'shipped_on' => now()]);
        }

        if ($request->expected_arrival)
        {
            $shipment->update(['expected_arrival' => Carbon::parse($request->expected_arrival)]);
        }

        $shipment->update([
            'tracking'        => $request->tracking,
            'vendor_sub'      => $request->vendor_sub,
            'vendor_shipping' => $request->vendor_shipping,
            'vendor_total'    => $request->vendor_total
        ]);
        if ($request->hasFile('vendor_invoice'))
        {
            $lo = new LoFileHandler();
            $file = $lo->createFromRequest($request, 'vendor_invoice', FileType::Invoice, $shipment->id);
            $shipment->update(['vendor_invoice' => $file->id]);
        }
        if ($request->email_customer)
        {
            $shipment->sendTracking();
        }
        return redirect()->to("/admin/shipments")->withMessage("Information Updated Successfully.");
    }

    /**
     * Close an order
     * @param Shipment $shipment
     * @return string[]
     */
    public function close(Shipment $shipment): array
    {
        $shipment->update(['active' => false, 'status' => ShipmentStatus::Arrived]);
        $shipment->order->logs()->create([
            'status' => OrderStatus::Completed,
            'user_id' => user()->id,
            'note' => "Order Completed/Closed"
        ]);
        $shipment->order()->update(['status' => OrderStatus::Completed]);
        return ['callback' => 'redirect:/admin/shipments'];
    }

    /**
     * Cancel an order
     * @param Shipment $shipment
     * @return string[]
     */
    public function destroy(Shipment $shipment): array
    {
        $shipment->update(['active' => false, 'status' => ShipmentStatus::Cancelled]);
        return ['callback' => 'redirect:/admin/shipments'];
    }
}
