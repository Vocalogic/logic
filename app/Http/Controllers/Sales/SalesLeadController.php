<?php

namespace App\Http\Controllers\Sales;

use App\Enums\Core\ActivityType;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class SalesLeadController extends Controller
{
    /**
     * Show leads for agent.
     * @return View
     */
    public function index(): View
    {
        return view('shop.sales.leads.index');
    }

    /**
     * Create new Lead
     * @return View
     */
    public function create(): View
    {
        return view('shop.sales.leads.create');
    }

    /**
     * Create new Lead from Agent
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'company' => 'required',
            'contact' => 'required',
            'email'   => 'required'
        ]);
        if (Lead::where('email', $request->email)->count())
        {
            throw new LogicException("This contact is already part of a different lead. Please check the email address");
        }
        $lead = (new Lead)->create([
            'company'        => $request->company,
            'contact'        => $request->contact,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'title'          => $request->title,
            'address'        => $request->address,
            'city'           => $request->city,
            'state'          => $request->state,
            'zip'            => $request->zip,
            'lead_status_id' => 1,
            'lead_type_id'   => 1,
            'agent_id'       => user()->id,
            'hash'           => uniqid("D-"),
        ]);
        sysact(ActivityType::Lead, $lead->id, "created lead ");
        return redirect()->to("/sales/leads/$lead->id");
    }

    /**
     * Update a Lead
     * @param Lead    $lead
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Lead $lead, Request $request): RedirectResponse
    {

        if (!$lead->active) abort(404);
        if ($lead->agent_id != user()->id) abort(401);
        if ($request->discovery)
        {
            $lead->update(['discovery' => $request->discovery]);
            sysact(ActivityType::Lead, $lead->id, "updated discovery notes for ");
            return redirect()->to("/sales/leads/$lead->id");
        }
        $request->validate([
            'company' => 'required',
            'contact' => 'required',
            'email'   => 'required'
        ]);
        $lead->update([
            'company' => $request->company,
            'contact' => $request->contact,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
            'city'    => $request->city,
            'state'   => $request->state,
            'zip'     => $request->zip

        ]);
        sysact(ActivityType::Lead, $lead->id, "updated lead ");
        return redirect()->to("/sales/leads/$lead->id");
    }

    /**
     * Show Lead
     * @param Lead $lead
     * @return View
     */
    public function show(Lead $lead): View
    {
        if (!$lead->active) abort(404);
        if ($lead->agent_id != user()->id) abort(401);
        return view('shop.sales.leads.show', ['lead' => $lead]);
    }

    /**
     * Save Questionnaire
     * @param Lead    $lead
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveQuestions(Lead $lead, Request $request) : RedirectResponse
    {
        if (!$lead->active) abort(404);
        if ($lead->agent_id != user()->id) abort(401);
        foreach ($request->all() as $key => $val)
        {
            if (str_contains($key, "d_"))
            {
                $key = explode("_", $key);
                $key = $key[1]; // d_1 gives 1
                $record = $lead->discoveries()->where('discovery_id', $key)->firstOrCreate(['discovery_id' => $key]);
                $record->update(['value' => $val]);
            }
        }
        sysact(ActivityType::Lead, $lead->id, "updated questionnaire for ");
        return redirect()->back();
    }

}
