<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discovery;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\LeadType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class LeadTypeController extends Controller
{
    /**
     * Show all lead types
     * @return View
     */
    public function index(): View
    {
        return view('admin.lead_types.index');
    }

    /**
     * Show create form
     * @return View
     */
    public function create(): View
    {
        return view('admin.lead_types.create')->with('type', new LeadType);
    }

    /**
     * Show Edit Form
     * @param LeadType $type
     * @return View
     */
    public function show(LeadType $type): View
    {
        return view('admin.lead_types.create')->with('type', $type);
    }

    /**
     * Store a new Lead Type
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required']);
        (new LeadType)->create([
            'name' => $request->name
        ]);
        return redirect()->to("/admin/lead_types");
    }

    /**
     * Update Lead Type
     * @param LeadType $type
     * @param Request  $request
     * @return RedirectResponse
     */
    public function update(LeadType $type, Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required']);
        $type->update(['name' => $request->name]);
        return redirect()->to("/admin/lead_types");
    }

    /**
     * Delete a lead type
     * @param LeadType $type
     * @return string[]
     */
    public function destroy(LeadType $type): array
    {
        Lead::where('lead_type_id', $type->id)->update(['lead_type_id' => 0]);
        Discovery::where('lead_type_id', $type->id)->delete();
        $type->delete();
        return ['callback' => "redirect:/admin/lead_types"];
    }

    /**
     * Store new status in the database.
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeStatus(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required']);
        $status = (new LeadStatus)->create([
            'name'             => $request->name,
            'disable_warnings' => $request->disable_warnings
        ]);
        switch ($request->lead_type)
        {
            case 'won' :
                $status->update(['is_won' => 1]);
                break;
            case 'lost' :
                $status->update(['is_lost' => 1]);
                break;
        }
        return redirect()->back()->with('message', "Status Created Successfully");
    }

    /**
     * Show status modal.
     * @param LeadStatus $status
     * @return View
     */
    public function showStatus(LeadStatus $status): View
    {
        return view('admin.lead_types.statuses.show', ['status' => $status]);
    }

    /**
     * Update status in the database.
     * @param LeadStatus $status
     * @param Request    $request
     * @return RedirectResponse
     */
    public function updateStatus(LeadStatus $status, Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required']);
        $status->update([
            'name'             => $request->name,
            'disable_warnings' => $request->disable_warnings
        ]);
        switch ($request->lead_type)
        {
            case 'won' :
                $status->update(['is_won' => 1, 'is_lost' => 0]);
                break;
            case 'lost' :
                $status->update(['is_lost' => 1, 'is_won' => 0]);
                break;
            default:
                $status->update(['is_lost' => 0, 'is_won' => 0]);
                break;
        }
        return redirect()->back()->with('message', "Status Updated Successfully");
    }

    /**
     * Remove a lead status.
     * @param LeadStatus $status
     * @return string[]
     */
    public function destroyStatus(LeadStatus $status): array
    {
        Lead::where('lead_status_id', $status->id)->update(['lead_status_id' => 1]);
        $status->delete();
        return ['callback' => "reload"];
    }


}
