<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\BillItem;
use App\Models\BillItemCoupon;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class CouponController extends Controller
{
    /**
     * Show coupons
     * @return View
     */
    public function index(): View
    {
        return view('admin.coupons.index');
    }

    /**
     * Show Coupon Create Form
     * @return View
     */
    public function create(): View
    {
        return view('admin.coupons.create', ['coupon' => new Coupon]);
    }

    /**
     * Show coupon
     * @param Coupon $coupon
     * @return View
     */
    public function show(Coupon $coupon): View
    {
        return view('admin.coupons.create', ['coupon' => $coupon]);
    }

    /**
     * Store new Coupon
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validateInput($request);
        if ($request->start) $request->merge(['start' => Carbon::parse($request->start)]);
        if ($request->end) $request->merge(['end' => Carbon::parse($request->end)]);
        $coupon = (new Coupon)->create($request->all());
        if (!$request->total_invoice)
        {
            return redirect()->to("/admin/coupons/$coupon->id");
        }
        else return redirect()->to("/admin/coupons");
    }

    /**
     * Update Coupon
     * @param Coupon  $coupon
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function update(Coupon $coupon, Request $request): RedirectResponse
    {
        $this->validateInput($request);
        if ($request->start) $request->merge(['start' => Carbon::parse($request->start)]);
        if ($request->end) $request->merge(['end' => Carbon::parse($request->end)]);

        $coupon->update($request->all());
        if (!$request->total_invoice)
        {
            return redirect()->to("/admin/coupons/$coupon->id");
        }
        else return redirect()->to("/admin/coupons");
    }

    /**
     * Validate Input
     * @param Request $request
     * @return void
     * @throws LogicException
     */
    private function validateInput(Request $request)
    {
        $request->validate([
            'name'   => "required",
            'coupon' => "required",
        ]);
        if (!$request->dollars_off && !$request->percent_off)
        {
            throw new LogicException("You must specify either a dollar amount or percentage to discount.");
        }
        if ($request->start)
        {
            try
            {
                $request->merge(['start' => Carbon::parse($request->start)]);

            } catch (\Exception $e)
            {
                throw new LogicException("Start date and time was invalid.");
            }
        }
        if ($request->end)
        {
            try
            {
                $request->merge(['end' => Carbon::parse($request->end)]);

            } catch (\Exception $e)
            {
                throw new LogicException("End date and time was invalid.");
            }
        }
    }

    /**
     * Add allowable item
     * @param Coupon  $coupon
     * @param Request $request
     * @return RedirectResponse
     */
    public function addItem(Coupon $coupon, Request $request): RedirectResponse
    {
        $request->validate([
            'bill_item_id' => 'required',
            'min_qty'      => 'required|numeric',
            'max_qty'      => 'required|numeric'
        ]);
        $coupon->items()->create([
            'bill_item_id' => $request->bill_item_id,
            'min_qty'      => $request->min_qty,
            'max_qty'      => $request->max_qty
        ]);
        return redirect()->back();
    }

    /**
     * Show edit modal
     * @param Coupon         $coupon
     * @param BillItemCoupon $item
     * @return View
     */
    public function editItem(Coupon $coupon, BillItemCoupon $item): View
    {
        return view('admin.coupons.item_modal', ['coupon' => $coupon, 'item' => $item]);
    }

    /**
     * Update allowable item.
     * @param Coupon         $coupon
     * @param BillItemCoupon $item
     * @param Request        $request
     * @return RedirectResponse
     */
    public function updateItem(Coupon $coupon, BillItemCoupon $item, Request $request): RedirectResponse
    {
        $request->validate([
            'bill_item_id' => 'required',
            'min_qty'      => 'required|numeric',
            'max_qty'      => 'required|numeric'
        ]);
        $item->update([
            'bill_item_id' => $request->bill_item_id,
            'min_qty'      => $request->min_qty,
            'max_qty'      => $request->max_qty
        ]);
        return redirect()->back();
    }

    /**
     * Remove Item
     * @param Coupon         $coupon
     * @param BillItemCoupon $item
     * @return array
     */
    public function delItem(Coupon $coupon, BillItemCoupon $item): array
    {
        $item->delete();
        return ['callback' => 'reload'];
    }
}
