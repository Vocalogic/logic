<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerInvoice;
use App\Operations\API\Control;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerController extends Controller
{
    /**
     * Show partner status page.
     * @return View
     */
    public function index(): View
    {
        return view('admin.partners.index');
    }

    /**
     * Create Partner Invitation
     * @return View
     */
    public function create(): View
    {
        return view('admin.partners.create');
    }

    /**
     * Attempt to create a new partner.
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     * @throws GuzzleException
     */
    public function store(Request $request): RedirectResponse
    {
        if (Partner::where('code', $request->code)->count())
        {
            throw new LogicException("This partner already exists.");
        }
        $request->validate(['code' => 'required']);
        $c = new Control();
        $partner = $c->getPartnerByCode($request->code);
        if (!$partner->success)
        {
            throw new LogicException("ERROR: " . $partner->message);
        }
        $partner = (new Partner)->create([
            'name'            => $partner->name,
            'partner_host'    => $partner->host,
            'code'            => $partner->partner_code,
            'originated_self' => true
        ]);
        return redirect()->to("/admin/partners/$partner->id");
    }

    /**
     * Update partner or manage invites
     * @param Partner $partner
     * @param Request $request
     * @return mixed
     * @throws GuzzleException
     * @throws LogicException
     */
    public function update(Partner $partner, Request $request): mixed
    {
        if ($request->createInvite)
        {
            $this->createInvite($partner, $request);
            $partner->update([
                'commission_out_mrc'   => $request->commission_out_mrc,
                'commission_out_spiff' => $request->commission_out_spiff,
                'status'               => "Pending Acceptance"
            ]);
            return redirect()->to("/admin/partners");
        }

        if ($request->acceptInvite)
        {
            // We are accepting the invite, sync up what we are giving out and let the invitee know.
            // This will complete the handshake between both parties

            $this->acceptInvite($partner, $request);
            $partner->update([
                'commission_out_mrc'   => $request->commission_out_mrc,
                'commission_out_spiff' => $request->commission_out_spiff,
                'status'               => "Accepted"
            ]);
        }
        return redirect()->to("/admin/partners");
    }

    /**
     * Show partner
     * @param Partner $partner
     * @return View
     */
    public function show(Partner $partner): View
    {
        return view('admin.partners.show', ['partner' => $partner]);
    }

    /**
     * Submit Invitation to Customer
     * @param Partner $partner
     * @param Request $request
     * @return void
     * @throws LogicException
     * @throws GuzzleException
     */
    private function createInvite(Partner $partner, Request $request): void
    {
        try
        {
            $partner->submitPartnerInvitation([
                'commission_out_mrc'   => $request->commission_out_mrc,
                'commission_out_spiff' => $request->commission_out_spiff,
                'net_days'             => $request->net_days,
                'note'                 => $request->note,
                'partner_code'         => license()->partner_code
            ]);
        } catch (Exception $e)
        {
            throw new LogicException($e->getMessage());
        }
    }

    /**
     * Accept invitation from partner
     * @param Partner $partner
     * @param Request $request
     * @return void
     * @throws LogicException|GuzzleException
     */
    private function acceptInvite(Partner $partner, Request $request) : void
    {
        try
        {
            $partner->submitPartnerAccept([
                'commission_out_mrc'   => $request->commission_out_mrc,
                'commission_out_spiff' => $request->commission_out_spiff,
                'net_days'             => $request->net_days,
                'note'                 => $request->note,
                'partner_code'         => license()->partner_code
            ]);
        } catch (Exception $e)
        {
            throw new LogicException($e->getMessage());
        }
    }

    /**
     * Get a remote invoice for viewing.
     * @param Partner $partner
     * @param int     $id
     * @return View
     * @throws GuzzleException
     */
    public function getRemoteInvoice(Partner $partner, int $id) : View
    {
        $invoice = $partner->getRemoteCommission($id);
        return view('admin.partners.partials.show_invoice', ['invoice' => $invoice, 'remote' => true]);
    }

    /**
     * Get a local invoice for viewing
     * @param Partner $partner
     * @param int     $id
     * @return View
     */
    public function getLocalInvoice(Partner $partner, int $id): View
    {
        $invoice = PartnerInvoice::find($id);
        return view('admin.partners.partials.show_invoice', ['invoice' => $invoice, 'remote' => false]);
    }
}
