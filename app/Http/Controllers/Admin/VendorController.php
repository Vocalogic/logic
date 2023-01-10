<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorController extends Controller
{
    /**
     * Show all hardware vendors.
     * @return View
     */
    public function index(): View
    {
        return view('admin.vendors.index');
    }

    /**
     * Create a new Vendor
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => 'required',
            'order_email' => 'required'
        ]);
        (new Vendor)->create($request->all());
        return redirect()->to("/admin/vendors");
    }

    /**
     * Update Vendor
     * @param Vendor  $vendor
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Vendor $vendor, Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => 'required',
            'order_email' => 'required'
        ]);
        $vendor->update($request->all());
        return redirect()->to("/admin/vendors");
    }

    /**
     * Show edit modal
     * @param Vendor $vendor
     * @return View
     */
    public function show(Vendor $vendor): View
    {
        return view('admin.vendors.show')->with('vendor', $vendor);
    }

    /**
     * Remove a vendor
     * @param Vendor $vendor
     * @return string[]
     */
    public function destroy(Vendor $vendor): array
    {
        $vendor->delete();
        return ['callback' => 'reload'];
    }

}
