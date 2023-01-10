<?php

namespace App\Http\Controllers\API;

use App\Enums\Core\ActivityType;
use App\Enums\Core\LeadStatus;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Activity;
use App\Models\Lead;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\Request;

class PartnerLeadController extends Controller
{
    /**
     * Receive a lead from a partner. Set as sourced by partner.
     * @param string  $code
     * @param Request $request
     * @return object
     */
    public function recvLead(string $code, Request $request): object
    {
        $lead = (object)$request->lead;
        if (Lead::where('email', $lead->email)->count())
        {
            return (object)[
                'success' => false,
                'message' => "Could not accept lead. A lead with the same email address has already been found."
            ];
        }
        $partner = Partner::where('code', $code)->first();
        if (!$partner || !$partner->active)
        {
            return (object)[
                'success' => false,
                'message' => "Could not accept Lead. Partnership is in a disabled state. Please contact partner."
            ];
        }
        $lead = (new Lead)->create([
            'company'         => $lead->company,
            'contact'         => $lead->contact,
            'email'           => $lead->email,
            'phone'           => $lead->phone,
            'title'           => $lead->title,
            'stage'           => $lead->stage,
            'logo_id'         => null,
            'active'          => 1,
            'agent_id'        => 1,
            'description'     => $lead->description,
            'address'         => $lead->address,
            'address2'        => $lead->address2,
            'city'            => $lead->city,
            'state'           => $lead->state,
            'zip'             => $lead->zip,
            'hash'            => $lead->hash,
            'lead_type_id'    => 0,
            'partner_id'      => $partner->id,
            'partner_sourced' => true,
            'discovery'       => $lead->discovery
        ]);
        $lead->refresh();
        sysact(ActivityType::Lead, $lead->id, "transferred a new lead from $partner->name:");
        template('system.partnerLead', User::find(1), [$partner, $lead]);
        return (object)[
            'success' => true,
            'message' => "Lead Transferred Successfully"
        ];
    }

    /**
     * This method will transmit a lead comment from one host to another.
     * @param string  $code
     * @param string  $hash
     * @param Request $request
     * @return object
     */
    public function recvLeadActivity(string $code, string $hash, Request $request): object
    {
        $lead = Lead::where('hash', $hash)->first();
        if (!$lead)
        {
            return (object)[
                'success' => false,
                'message' => "Could not update lead. This lead has been removed or is no longer available."
            ];
        }
        $partner = Partner::where('code', $code)->first();
        if (!$partner || !$partner->active)
        {
            return (object)[
                'success' => false,
                'message' => "Could not accept Lead. Partnership is in a disabled state. Please contact partner."
            ];
        }
        if (!$request->message)
        {
            return (object)[
                'success' => false,
                'message' => "A message is required."
            ];
        }
        (new Activity)->create([
            'type'       => 'LEAD',
            'refid'      => $lead->id,
            'user_id'    => 0,
            'post'       => $request->message,
            'system'     => 0,
            'private'    => 1,
            'partner_id' => $partner->id
        ]);
        return (object)[
            'success' => true,
            'message' => "Lead Activity Recorded"
        ];
    }

    /**
     * Get Lead Information for Requesting Partner
     * @param string $code
     * @param string $hash
     * @return object
     */
    public function getLead(string $code, string $hash): object
    {
        $lead = Lead::where('hash', $hash)->first();
        if (!$lead)
        {
            return (object)[
                'success' => false,
                'message' => "Could not update lead. This lead has been removed or is no longer available."
            ];
        }
        $partner = Partner::where('code', $code)->first();
        if (!$partner || !$partner->active)
        {
            return (object)[
                'success' => false,
                'message' => "Could not get Lead. Partnership is in a disabled state. Please contact partner."
            ];
        }
        if ($lead->partner_id != $partner->id)
        {
            return (object)[
                'success' => false,
                'message' => "Lead information cannot be retrieved at this time. Please contact your partner for more details."
            ];
        }
        $quoteInformation = [];
        if ($lead->quotes()->where('archived', false)->count())
        {
            foreach ($lead->quotes()->where('archived', false)->get() as $quote)
            {
                $quoteInformation[] = [
                    'mrr'    => $quote->mrr,
                    'nrc'    => $quote->nrc,
                    'number' => $quote->id
                ];
            }
        }
        return (object)[
            'success' => true,
            'lead'    => [
                'assigned_to'   => $lead->agent ? $lead->agent->name : "Unassigned",
                'last_updated'  => $lead->updated_at->format("m/d/y h:ia"),
                'active_quotes' => $quoteInformation,
                'forecast_date' => $lead->forecast_date
            ]
        ];
    }

    /**
     * Get Lead Information for Requesting Partner
     * @param string $code
     * @param string $hash
     * @return object
     */
    public function disconnectLead(string $code, string $hash): object
    {
        // The remote partner must have marked this as lost.
        $lead = Lead::where('hash', $hash)->first();
        if (!$lead)
        {
            return (object)[
                'success' => false,
                'message' => "Could not update lead. This lead has been removed or is no longer available."
            ];
        }
        $partner = Partner::where('code', $code)->first();
        if (!$partner || !$partner->active)
        {
            return (object)[
                'success' => false,
                'message' => "Could not get Lead. Partnership is in a disabled state. Please contact partner."
            ];
        }
        if ($lead->partner_id != $partner->id)
        {
            return (object)[
                'success' => false,
                'message' => "Lead information cannot be retrieved at this time. Please contact your partner for more details."
            ];
        }
        $lead->update(['partner_id' => 0]);
        sysact(ActivityType::Lead, $lead->id,
            "marked lead as lost via Partner $partner->name. (Lead Unassigned from Partner): ");
        return (object)[
            'success' => true,
            'message' => "Unbound Lead from Partner Status"
        ];
    }

    /**
     * Mark our lead as sold as told by our partner.
     * @param string $code
     * @param string $hash
     * @return object
     */
    public function soldLead(string $code, string $hash): object
    {
        // The remote partner sold their lead. We will just archive our side.
        $lead = Lead::where('hash', $hash)->first();
        if (!$lead)
        {
            return (object)[
                'success' => false,
                'message' => "Could not update lead. This lead has been removed or is no longer available."
            ];
        }
        $partner = Partner::where('code', $code)->first();
        if (!$partner || !$partner->active)
        {
            return (object)[
                'success' => false,
                'message' => "Could not get Lead. Partnership is in a disabled state. Please contact partner."
            ];
        }
        if ($lead->partner_id != $partner->id)
        {
            return (object)[
                'success' => false,
                'message' => "Lead information cannot be retrieved at this time. Please contact your partner for more details."
            ];
        }
        $lead->update(['active' => 0, 'stage' => 'Sold by Partner']);
        sysact(ActivityType::Lead, $lead->id, "marked lead as sold by $partner->name for ");
        return (object)[
            'success' => true,
            'message' => "Lead marked as sold by partner"
        ];
    }



}
