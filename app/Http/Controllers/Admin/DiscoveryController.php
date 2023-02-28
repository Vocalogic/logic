<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discovery;
use App\Models\LeadDiscovery;
use App\Models\LeadType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class DiscoveryController extends Controller
{
    /**
     * Show all dicovery questions
     * @return View
     */
    public function index(): View
    {
        return view('admin.discovery.index');
    }

    /**
     * Show edit modal for discovery
     * @param Discovery $discovery
     * @return View
     */
    public function show(Discovery $discovery): View
    {
        return view('admin.discovery.show', ['discovery' => $discovery]);
    }

    /**
     * Update discovery question
     * @param Discovery $discovery
     * @param Request   $request
     * @return RedirectResponse
     */
    public function update(Discovery $discovery, Request $request): RedirectResponse
    {
        $request->validate(['question' => 'required']);
        $discovery->update([
            'question'     => $request->question,
            'type'         => $request->type,
            'opts'         => $request->opts,
            'help'         => $request->help
        ]);
        return redirect()->to("/admin/discovery")->with('message', 'Discovery Question updated.');
    }

    /**
     * Create new Question
     * @param LeadType $type
     * @param Request  $request
     * @return RedirectResponse
     */
    public function store(LeadType $type, Request $request): RedirectResponse
    {
        $request->validate(['question' => 'required']);
        (new Discovery)->create([
            'question'     => $request->question,
            'lead_type_id' => $type->id,
            'type'         => $request->type,
            'opts'         => '',
            'help'         => ''
        ]);
        return redirect()->to("/admin/discovery")->with('message', 'Discovery Question added.');
    }



    /**
     * Delete a question
     * @param Discovery $discovery
     * @return string[]
     */
    public function destroy(Discovery $discovery): array
    {
        LeadDiscovery::where('discovery_id', $discovery->id)->delete();
        $discovery->delete();
        return ['callback' => 'reload'];
    }



}
