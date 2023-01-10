<?php

namespace App\Http\Controllers\API;

use App\Enums\Core\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\PartnerInvoice;
use Illuminate\Http\Request;

class PartnerAccountController extends Controller
{
    /**
     * Get a list of accounts assigned to a partner.
     * @param string $code
     * @return object
     */
    public function getAccounts(string $code): object
    {
        $partner = Partner::where('code', $code)->first();
        if (!$partner || !$partner->active)
        {
            return (object)[
                'success' => false,
                'message' => "Could not get Lead. Partnership is in a disabled state. Please contact partner."
            ];
        }
        $accounts = [];
        foreach (Account::where('active', true)->where('partner_id', $partner->id)->get() as $account)
        {
            $accounts[] = [
                'name'       => $account->name,
                'created'    => $account->created_at->format("F d, Y"),
                'mrr'        => $account->mrr,
                'next_bill'  => $account->next_bill?->format("F d, Y"),
                'agent'      => $account->agent ? $account->agent->name : "Unassigned",
                'commission' => $account->commissionable
            ];
        }
        return (object)[
            'success'  => true,
            'accounts' => $accounts
        ];
    }

    /**
     * Get invoices assigned to a partner.
     * @param string $code
     * @return object
     */
    public function getInvoices(string $code): object
    {
        $partner = Partner::where('code', $code)->first();
        if (!$partner || !$partner->active)
        {
            return (object)[
                'success' => false,
                'message' => "Could not get Lead. Partnership is in a disabled state. Please contact partner."
            ];
        }
        $invoices = [];
        foreach (Account::where('active', true)->where('partner_id', $partner->id)->get() as $account)
        {
            if ($account->spiffed && $partner->commission_out_spiff > 0) continue; // Already spiffed. Can't spiff anymore
            $count = 0;
            foreach ($account->invoices()->where('status', '!=', InvoiceStatus::DRAFT->value)->get() as $invoice)
            {
                // First we need to make sure we're only doing services here.. Products do not count for commissions
                if ($invoice->servicesTotal <= 0) continue; // Not a MRR invoice.
                // If we are spiffing here we only want the FIRST invoice.
                $count++;
                if ($partner->commission_out_spiff > 0 && $count > 1) continue; // Only show first invoice if spiffed.
                $payout = $invoice->getPartnerPayout($partner);
                $invoices[] = [
                    'account'      => $invoice->account->name,
                    'number'       => $invoice->id,
                    'services'     => $invoice->servicesTotal,
                    'payout'       => $payout,
                    'status'       => $invoice->status,
                    'due_on'       => $invoice->due_on ? $invoice->due_on->timestamp : null,
                    'sent_on'      => $invoice->sent_on ? $invoice->sent_on->timestamp : null,
                    'paid_on'      => $invoice->paid_on ? $invoice->paid_on->timestamp : null,
                    'commissioned' => (bool)$invoice->partner_invoice_id
                ];
            }
        }
        return (object)[
            'success'  => true,
            'invoices' => $invoices
        ];
    }

    /**
     * Our partner will be sending a group of ids. We will do our own verification that
     * these invoices should be paid out. If not, we will skip that invoice.
     * @param string  $code
     * @param Request $request
     * @return object
     */
    public function requestCommission(string $code, Request $request): object
    {
        $partner = Partner::where('code', $code)->first();
        if (!$partner || !$partner->active)
        {
            return (object)[
                'success' => false,
                'message' => "Could not get Lead. Partnership is in a disabled state. Please contact partner."
            ];
        }
        $ids = $request->ids;
        // These are our local ids.
        if (!is_array($ids))
        {
            return (object)[
                'success' => false,
                'message' => "No invoices requested for commission payout."
            ];
        }
        // Step 1: We will validate each of the ids requested.
        $valid = [];
        foreach ($ids as $id)
        {
            $invoice = Invoice::find($id);
            if (!$invoice) continue;              // Invoice gone for some reason
            if ($invoice->balance > 0) continue;  // Not paid in full
            if ($invoice->partner_invoice_id) continue;
            if ($invoice->account->spiffed && $partner->commission_out_spiff) continue; // Already spiffed.
            // We won't check net days here. Just making sure we're not duplicating any payments.
            // timing isn't as important here as thats handled by the monthly cron.
            $valid[] = $invoice;
        }
        if (!count($valid))
        {
            return (object)[
                'success' => false,
                'message' => "No invoices passed validation for creating."
            ];
        }
        $piv = (new PartnerInvoice)->create([
            'partner_id' => $partner->id,
            'hash'       => uniqid('PIV-'),
            'status'     => "Unpaid",
        ]);
        foreach ($valid as $invoice)
        {
            // First add this invoice commission as an item.
            $piv->items()->create([
                'name'   => sprintf("Invoice #%d for %s", $invoice->id, $invoice->account->name),
                'amount' => $invoice->getPartnerPayout($partner)
            ]);
            // Update invoice so we don't double commission
            $invoice->update(['partner_invoice_id' => $piv->id]);
            if ($partner->commission_out_spiff)
            {
                $invoice->account->update(['spiffed' => true]);
            }
        }
        return (object)[
            'success' => true,
            'message' => "Partner Invoice Created"
        ];
    }

    /**
     * Get list of commissions from remote.
     * @param string $code
     * @return object
     */
    public function getCommissions(string $code): object
    {
        $partner = Partner::where('code', $code)->first();
        if (!$partner || !$partner->active)
        {
            return (object)[
                'success' => false,
                'message' => "Could not get Commissions. Partnership is in a disabled state. Please contact partner."
            ];
        }
        $comms = [];
        foreach (PartnerInvoice::where('partner_id', $partner->id)->orderBy('created_at', 'DESC')->get() as $comm)
        {
            $comms[] = [
                'id'      => $comm->id,
                'amount'  => $comm->total,
                'status'  => $comm->status,
                'paid_on' => $comm->paid_on ? $comm->paid_on->timestamp : null
            ];
        }
        return (object)[
            'success'     => true,
            'message'     => "Commissions Found",
            'commissions' => $comms
        ];
    }

    /**
     * Get detail on a single commission.
     * @param string $code
     * @param int    $id
     * @return object
     */
    public function getCommission(string $code, int $id): object
    {
        $partner = Partner::where('code', $code)->first();
        if (!$partner || !$partner->active)
        {
            return (object)[
                'success' => false,
                'message' => "Could not get Commission. Partnership is in a disabled state. Please contact partner."
            ];
        }

        $pi = PartnerInvoice::with('items')->where('partner_id', $partner->id)->where('id', $id)->first();
        if (!$pi)
        {
            return (object)[
                'success' => false,
                'message' => "Could not get Commission. Partner Invoice not found."
            ];
        }
        return (object)[
            'success'    => true,
            'message'    => "Commission Found",
            'commission' => $pi
        ];
    }


}
