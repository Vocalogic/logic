<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\CommissionStatus;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissionController extends Controller
{
    /**
     * Show commissions
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        if ($request->status)
        {
            $commissions = Commission::where('status', CommissionStatus::from($request->status))->get();
        }
        elseif ($request->byUser)
        {
            $commissions = Commission::where('user_id', $request->byUser)->get();
        }
        else
        {
            $commissions = Commission::where('status', '!=', CommissionStatus::Paid)->get();
        }

        foreach ($commissions as $comm)
        {
            if (!$comm->invoice) $comm->delete();
        }

        return view('admin.finance.commissions.index', ['commissions' => $commissions]);
    }

    /**
     * Show commission editor modal
     * @param Commission $commission
     * @return View
     */
    public function show(Commission $commission): View
    {
        return view('admin.finance.commissions.show', ['commission' => $commission]);
    }

    /**
     * Update Commission Properties
     * @param Commission $commission
     * @param Request    $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function update(Commission $commission, Request $request): RedirectResponse
    {
        if (($request->amount != $commission->amount) && !$request->edit_note)
        {
            throw new LogicException("You must include a reason why you are changing the amount.");
        }
        $commission->update([
            'amount'    => convertMoney($request->amount),
            'edit_note' => $request->edit_note
        ]);
        if ($request->commission_batch_id)
        {
            $commission->update(['commission_batch_id' => $request->commission_batch_id]);
        }
        return redirect()->back()->with('message', "Commission updated successfully.");
    }

}
