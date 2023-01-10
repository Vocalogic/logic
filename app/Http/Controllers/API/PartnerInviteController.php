<?php

namespace App\Http\Controllers\API;

use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Operations\API\Control;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class PartnerInviteController extends Controller
{
    /**
     * Receive an Invite from another Logic Instance
     * @param Request $request
     * @return object
     * @throws LogicException
     * @throws GuzzleException
     */
    public function recvInvite(Request $request): object
    {
        // First lets validate their code.
        if (!$request->partner_code)
        {
            return (object)[
                'success' => false,
                'message' => "Partner access code not Found"
            ];
        }
        $c = new Control();
        $remotePartner = $c->getPartnerByCode($request->partner_code);
        if (Partner::where('code', $request->partner_code)->count())
        {
            return (object)[
                'success' => false,
                'message' => "Partner already exists. Invitation declined."
            ];
        }
        $partner = (new Partner)->create([
            'code'                 => $request->partner_code,
            'name'                 => $remotePartner->name,
            'partner_host'         => $remotePartner->host,
            'commission_in_mrc'    => $request->commission_out_mrc, // what they pay out we accept in,
            'commission_in_spiff'  => $request->commission_out_spiff,
            'commission_out_mrc'   => 0,
            'commission_out_spiff' => 0, // by default
            'invited_on'           => now(),
            'originated_self'      => false, // We need to accept this - not originated by us.
            'status'               => "Pending Acceptance"
        ]);
        // Notify admin of a new partner.
        $partner->notifyNewInvite();
        return (object)[
            'success' => true,
            'message' => "Invitation sent successfully."
        ];
    }

    /**
     * Accept Invitiation
     * @param Request $request
     * @return object
     * @throws GuzzleException
     * @throws LogicException
     */
    public function acceptInvite(Request $request): object
    {
        // First lets validate their code.
        if (!$request->partner_code)
        {
            return (object)[
                'success' => false,
                'message' => "Partner access code not Found"
            ];
        }
        $c = new Control();
        try
        {
            $c->getPartnerByCode($request->partner_code);
        } catch (Exception $e)
        {
            return (object)[
                'success' => false,
                'message' => "There was a problem finding your partner's access code."
            ];
        }
        $partner = Partner::where('code', $request->partner_code)->first();
        if (!$partner)
        {
            return (object)[
                'success' => false,
                'message' => "Partner not found. Please contact the partner for more information."
            ];
        }
        $partner->update([
            'status'              => "Accepted",
            'commission_in_mrc'   => $request->commission_out_mrc, // what they pay out we accept in,
            'commission_in_spiff' => $request->commission_out_spiff,
            'accepted_on'         => now()
        ]);
        return (object)[
            'success' => true,
            'message' => "Partnership Established."
        ];
    }

}
