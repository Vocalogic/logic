<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\CommissionStatus;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\CommissionBatch;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommissionBatchController extends Controller
{
    /**
     * Build a new commission batch based on non-batched commissions
     * @return View
     */
    public function create(): View
    {
        return view('admin.commission_batches.create');
    }

    /**
     * Create new batch(es).
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // First we need to run through and figure out how many users need a batch created.
        $u = [];

        foreach ($request->all() as $key => $val)
        {
            if (str_contains($key, "c_"))
            {
                $x = explode("c_", $key);
                $cid = $x[1];
                $com = Commission::find($cid);
                if (!in_array($com->user_id, $u))
                {
                    $u[] = $com->user_id;
                }
            }
        }
        // Ok, now we have an array of users that need batches.. We'll go through them one at a time.
        foreach ($u as $user)
        {
            $batch = (new CommissionBatch)->create([
                'user_id' => $user,
            ]);
            foreach ($request->all() as $key => $val)
            {
                if (str_contains($key, "c_"))
                {
                    $x = explode("c_", $key);
                    $cid = $x[1];
                    $com = Commission::find($cid);
                    if ($com->user_id == $user)
                    {
                        $com->update(['commission_batch_id' => $batch->id]);
                    }
                }
            }
            $batch->refresh();
            $batch->notifyNew();
        }
        return redirect()->back()->with('message', "Batch(es) created successfully.");
    } // store

    /**
     * Edit Batch or Submit Payment
     * @param CommissionBatch $commissionBatch
     * @return View
     */
    public function show(CommissionBatch $commissionBatch): View
    {
        return view('admin.commission_batches.show', ['batch' => $commissionBatch]);
    }

    /**
     * Update Commission Batch (remove any items not on the list from submit)
     * @param CommissionBatch $commissionBatch
     * @param Request         $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function update(CommissionBatch $commissionBatch, Request $request): RedirectResponse
    {
        // First lets see if anything was left out (i.e turned off)
        foreach ($commissionBatch->commissions as $commission)
        {
            $key = "c_$commission->id";
            if (!$request->$key)
            {
                $commission->update(['commission_batch_id' => null]);
            }

        }
        if ($request->paid_on)
        {
            // We paid this commission
            if (!$request->transaction_detail)
            {
                throw new LogicException("You must provide transaction details when paying a commission.");
            }
            $commissionBatch->update([
                'paid_on'            => Carbon::parse($request->paid_on),
                'transaction_detail' => $request->transaction_detail,
                'paid_by'            => user()->id
            ]);
            foreach ($commissionBatch->commissions as $commission)
            {
                $commission->update([
                   'status' => CommissionStatus::Paid,
                   'active' => false,
                ]);
            }
            $commissionBatch->refresh();
            $commissionBatch->notifyPaid();
        }
        return redirect()->back()->with('message', "Batch Updated Successfully");
    }

}
