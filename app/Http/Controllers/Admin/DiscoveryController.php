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
     * Create new Question
     * @param LeadType $type
     * @param Request  $request
     * @return RedirectResponse
     */
    public function store(LeadType $type, Request $request): RedirectResponse
    {
        (new Discovery)->create([
            'question'     => $request->question,
            'lead_type_id' => $type->id,
            'type'         => $request->type,
            'opts'         => '',
            'help'         => ''
        ]);
        return redirect()->to("/admin/discovery");
    }

    /**
     * Update Live X-Editable
     * @param Discovery $discovery
     * @param Request   $request
     * @return bool[]
     */
    public function live(Discovery $discovery, Request $request):array
    {
        $discovery->update([$request->name => $request->value]);
        return ['success' => true];
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
