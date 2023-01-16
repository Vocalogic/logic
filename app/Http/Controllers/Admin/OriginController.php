<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadOrigin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class OriginController extends Controller
{
    /**
     * Store a new Lead Origin
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required']);
        (new LeadOrigin)->create([
            'name' => $request->name
        ]);
        return redirect()->back();
    }

    /**
     * Show Lead Modal
     * @param LeadOrigin $origin
     * @return View
     */
    public function show(LeadOrigin $origin) : View
    {
        return view('admin.lead_types.origins.show')->with('origin', $origin);
    }

    /**
     * Update Origin
     * @param LeadOrigin $origin
     * @param Request    $request
     * @return RedirectResponse
     */
    public function update(LeadOrigin $origin, Request $request): RedirectResponse
    {
        $origin->update(['name' => $request->name]);
        return redirect()->back();
    }

    /**
     * Remove an Origin
     * @param LeadOrigin $origin
     * @return string[]
     */
    public function destroy(LeadOrigin $origin): array
    {
        $origin->delete();
        return ['callback' => "reload"];
    }

}
