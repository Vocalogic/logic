<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\ActivityType;
use App\Enums\Core\LogSeverity;
use App\Enums\Files\FileType;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Partner;
use App\Models\User;
use App\Operations\Core\LoFileHandler;
use App\Operations\Integrations\Accounting\Finance;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class LeadController extends Controller
{
    /**
     * Show leads board
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $hasPartners = Partner::count() > 0;
        if (!$request->status)
        {
            $leads = Lead::where('active', true)->get();
        }
        else
        {
            $leads = Lead::where('lead_status_id', $request->status)->get();
        }
        return view('admin.leads.index', ['hasPartners' => $hasPartners, 'leads' => $leads]);
    }

    /**
     * Show create form for new lead.
     * @return View
     */
    public function create(): View
    {
        return view('admin.leads.create')->with('lead', new Lead);
    }

    /**
     * Show Lead
     * @param Lead    $lead
     * @param Request $request
     * @return View
     */
    public function show(Lead $lead, Request $request): View
    {
        if ($lead->partner && !$lead->partner_sourced)
        {
            return view('admin.leads.partner.show', ['lead' => $lead]);
        }

        $tab = $request->tab ?: 'overview';
        return view('admin.leads.show', ['lead' => $lead, 'tab' => $tab]);
    }

    /**
     * Update lead stats
     * @param Lead    $lead
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function update(Lead $lead, Request $request): RedirectResponse
    {
        $old = $lead->replicate();
        if ($request->forecast_date)
        {
            $request->merge(['forecast_date' => Carbon::parse($request->forecast_date)]);
        }
        if ($request->email)
        {
            $request->merge(['email' => strtolower($request->email)]);
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL))
            {
                throw new LogicException("A valid email address is required.");
            }
        }
        if ($request->email && User::where('email', $request->email)->count())
        {
            throw new LogicException("This email already exists as an account and cannot be set for this lead.");
        }
        $lead->update($request->all());
        // Go through and recalculate tax on each quote in case a state or taxation was changed.
        foreach ($lead->quotes as $quote)
        {
            $quote->calculateTax();
        }
        _log($lead, "Lead Details Updated", $old);
        return redirect()->to("/admin/leads/$lead->id")->with('message', $lead->company . " updated successfully.");
    }

    /**
     * Create new Lead
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'company'      => 'required',
            'contact'      => 'required',
            'lead_type_id' => 'required',
            'email'        => 'required'
        ]);
        $request->merge(['email' => strtolower($request->email)]);
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL))
        {
            throw new LogicException("A valid email address is required.");
        }
        if (User::where('email', $request->email)->count())
        {
            throw new LogicException("This email already exists as an account and cannot be set for this lead.");
        }
        $lead = (new Lead)->create([
            'type'               => $request->type,
            'company'            => $request->company,
            'contact'            => $request->contact,
            'email'              => $request->email,
            'phone'              => $request->phone,
            'title'              => $request->title,
            'lead_status_id'     => 1,
            'agent_id'           => user()->id,
            'lead_type_id'       => $request->lead_type_id,
            'hash'               => uniqid("D-"),
            'lead_origin_id'     => $request->lead_origin_id,
            'lead_origin_detail' => $request->lead_origin_detail
        ]);
        sysact(ActivityType::Lead, $lead->id, "created lead ");
        _log($lead, "Lead Created");
        return redirect()->to("/admin/leads/$lead->id")->with('message', $request->company . " created successfully.");
    }

    /**
     * Upload a logo for a lead.
     * @param Lead    $lead
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function uploadLogo(Lead $lead, Request $request): RedirectResponse
    {
        if (!$request->hasFile('logo'))
        {
            throw new LogicException("You must select a logo to upload.");
        }
        $lo = new LoFileHandler();
        if ($lead->logo_id)
        {
            $lo->delete($lead->logo_id);
        }
        $file = $lo->createFromRequest($request, 'logo', FileType::Image, $lead->id);
        $lo->unlock($file);
        $lead->update(['logo_id' => $file->id]);
        _log($lead, "New Logo Uploaded");
        _log($lead, "New Logo Uploaded", null, "File ID $file->id ($file->filename) Size: $file->filesize bytes",
            LogSeverity::Debug);
        return redirect()->to("/admin/leads/$lead->id");
    }

    /**
     * Update Discovery for leads
     * @param Lead    $lead
     * @param Request $request
     * @return array
     */
    public function updateDiscovery(Lead $lead, Request $request): array
    {
        $disc = $lead->discoveries()->where('discovery_id', $request->pk)->first();
        if (!$disc)
        {
            $disc = $lead->discoveries()->create([
                'discovery_id' => $request->pk,
                'value'        => $request->value
            ]);
            _log($disc, "Discovery Question Answered");
        }
        else
        {
            $old = $disc->replicate();
            $disc->update(['value' => $request->value]);
            _log($disc, "Discovery Question Updated", $old);
        }
        return ['success' => true];
    }

    /**
     * Store Rating
     * @param Lead    $lead
     * @param Request $request
     * @return string[]
     */
    public function rating(Lead $lead, Request $request)
    {
        $old = $lead->replicate();
        $lead->update(['rating' => $request->value]);
        _log($lead, "Lead Rating Updated", $old);
        return ['success' => "Rating Updated"];
    }

    /**
     * Send Discovery Request
     * @param Lead $lead
     * @return string[]
     */
    public function sendDiscovery(Lead $lead): array
    {
        $lead->sendDiscovery();
        _log($lead, "Discovery Questionnaire Sent to $lead->contact");
        return ['callback' => 'reload'];
    }


    /**
     * Close or Suspend Lead
     * @param Lead    $lead
     * @param Request $request
     * @return RedirectResponse
     */
    public function close(Lead $lead, Request $request): RedirectResponse
    {
        $request->validate(['reason' => 'required']);
        $old = $lead->replicate();
        if (!$request->reactivate_on)
        {
            $lead->update([
                    'lead_status_id' => $request->lead_status_id,
                    'reason'         => $request->reason,
                    'lost_on'        => now(),
                    'active'         => 0
                ]
            );
            sysact(ActivityType::Lead, $lead->id, "marked lead as lost ($request->reason) for ");
            _log($lead, "Lead marked as lost without reactivation", $old);
        }
        else
        {
            $conv = Carbon::parse($request->reactivate_on);
            $lead->update([
                'lead_status_id' => $request->lead_status_id,
                'reason'         => $request->reason,
                'lost_on'        => now(),
                'reactivate_on'  => $conv,
                'active'         => 0
            ]);
            sysact(ActivityType::Lead, $lead->id,
                "suspended lead ($request->reason). Reactivation scheduled for " . $conv->format("m/d/y") . " for ");
            _log($lead, "Lead marked as lost with reactivation date", $old);
        }
        if ($lead->partner)
        {
            $lead->partner->disconnectLead($lead);
            $lead->update(['partner_id' => 0]);
        }
        if ($lead->finance_customer_id)
        {
            _log($lead, "Removing Lead from Finance System", null,
                "Remote Finance Customer ID: $lead->finance_customer_id", LogSeverity::Debug);
            Finance::removeLead($lead);
        }
        return redirect()->to("/admin/leads");
    }

    /**
     * Show Partner Select Modal
     * @param Lead $lead
     * @return View
     */
    public function partnerModal(Lead $lead): View
    {
        return view('admin.leads.partner_modal', ['lead' => $lead]);
    }

    /**
     * We will attempt to transmit the lead to the partner. If so we will set it here.
     * @param Lead    $lead
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     * @throws GuzzleException
     */
    public function setPartner(Lead $lead, Request $request): RedirectResponse
    {
        $request->validate(['partner_id' => "required"]);
        $partner = Partner::find($request->partner_id);
        $lead->submitToPartner($partner);
        return redirect()->back()->with('message', "Lead Accepted by Partner");
    }

    /**
     * Save Discovery Information
     * @param Lead    $lead
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveDiscovery(Lead $lead, Request $request): RedirectResponse
    {
        $old = $lead->replicate();
        $lead->update(['discovery' => $request->discovery]);
        _log($lead, "Discovery Information Updated", $old);
        return redirect()->back()->with('message', 'Discovery information saved.');
    }

    /**
     * Show lead status modal
     * @param Lead $lead
     * @return View
     */
    public function showStatus(Lead $lead): View
    {
        return view('admin.leads.status_modal', ['lead' => $lead]);
    }

    /**
     * Set a new status on a lead
     * @param Lead    $lead
     * @param Request $request
     * @return RedirectResponse
     */
    public function setStatus(Lead $lead, Request $request): RedirectResponse
    {
        $old = $lead->replicate();
        $lead->setStatus($request->lead_status_id);
        _log($lead, "Status Changed", $old);
        return redirect()->back()->with('message', "Lead Status Updated");
    }

    /**
     * Reactivate a lost lead.
     * @param Lead $lead
     * @return string[]
     */
    public function activate(Lead $lead): array
    {
        $lead->update(['active' => 1, 'lead_status_id' => 1]);
        sysact(ActivityType::Lead, $lead->id, "reactivated lead ");
        _log($lead, "Lead was reactivated.");
        return ['callback' => "reload"];
    }

    /**
     * Import New Lead List from CSV
     * @return View
     */
    public function importModal(): View
    {
        return view('admin.leads.import_modal');
    }

    /**
     * Import Leads
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'lead_status_id' => 'required',
            'lead_type_id'   => 'required'
        ]);
        if (!$request->hasFile('import_file'))
        {
            throw new LogicException("You must attach a CSV for importing.");
        }

        $file = fopen($request->file('import_file')->getRealPath(), 'r');
        $count = 0;
        while (($line = fgetcsv($file)) !== false)
        {
            if ($line[0] == 'Company Name') continue;                // Ignore Headers
            if (Lead::where('company', $line[0])->count()) continue; // Don't duplicate
            if (!$line[1]) continue;
            if (!$line[9]) continue; // Make sure we have all fields
            (new Lead)->create([
                'company'        => $line[0],
                'contact'        => $line[1],
                'phone'          => $line[2],
                'email'          => $line[3],
                'website'        => $line[4],
                'address'        => $line[5],
                'address2'       => $line[6],
                'city'           => $line[7],
                'state'          => $line[8],
                'zip'            => $line[9],
                'active'         => $request->active ? 1 : 0,
                'lead_type_id'   => $request->lead_type_id,
                'lead_status_id' => $request->lead_status_id,
                'agent_id'       => user()->id,
                'hash'           => uniqid('D-')
            ]);
            $count++;
        }
        return redirect()->back()->with('message', $count . " Leads Imported Successfully");
    }
}
