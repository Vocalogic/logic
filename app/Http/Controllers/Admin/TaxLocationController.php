<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxLocation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaxLocationController extends Controller
{
    /**
     * Show all tax locations
     * @return View
     */
    public function index(): View
    {
        return view('admin.tax_locations.index');
    }

    /**
     * Show create modal for a new tax location.
     * @return View
     */
    public function create(): View
    {
        return view('admin.tax_locations.create', ['location' => new TaxLocation]);
    }

    /**
     * Create a new Tax Location
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['location' => 'required', 'rate' => 'required']);
        (new TaxLocation)->create([
            'location' => $request->location,
            'rate' => onlyNumbers($request->rate) // strip any % if someone put that in, etc.
        ]);
        return redirect()->to("/admin/tax_locations")->with('message', $request->location . " added to tax locations.");
    }

    /**
     * Show tax location edit modal
     * @param TaxLocation $taxLocation
     * @return View
     */
    public function show(TaxLocation $taxLocation) : View
    {
        return view('admin.tax_locations.create', ['location' => $taxLocation]);
    }

    /**
     * Update a tax location
     * @param TaxLocation $taxLocation
     * @param Request     $request
     * @return RedirectResponse
     */
    public function update(TaxLocation $taxLocation, Request $request): RedirectResponse
    {
        $request->validate(['location' => 'required', 'rate' => 'required']);
        $taxLocation->update([
            'location' => $request->location,
            'rate' => onlyNumbers($request->rate)
        ]);
        return redirect()->to("/admin/tax_locations")->with('message', $request->location . " updated.");
    }

    /**
     * Tax locations can be destroyed safely because tax is figured
     * on each update to an invoice. If the location disappears, no tax
     * will be applied to that invoice/quote.
     * @param TaxLocation $taxLocation
     * @return array
     */
    public function destroy(TaxLocation $taxLocation) : array
    {
        session()->flash("message", $taxLocation->location . " removed from tax locations.");
        $taxLocation->delete();
        return ['callback' => 'reload'];
    }
}
