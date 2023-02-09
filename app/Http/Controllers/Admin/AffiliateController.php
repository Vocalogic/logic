<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    /**
     * Show all affiliates
     * @return View
     */
    public function index(): View
    {
        return view('admin.affiliates.index');
    }

    /**
     * Create modal for a new affiliate
     * @return View
     */
    public function create(): View
    {
        return view('admin.affiliates.create', ['affiliate' => new Affiliate()]);
    }

    /**
     * Show affiliate editor
     * @param Affiliate $affiliate
     * @return View
     */
    public function show(Affiliate $affiliate) : View
    {
        return view('admin.affiliates.create', ['affiliate' => $affiliate]);
    }

    /**
     * Store a new affiliate
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required']);
        (new Affiliate)->create($request->all());
        return redirect()->to("/admin/affiliates")->with('message', $request->name . " created as an affiliate.");
    }

    /**
     * Update an affiliate
     * @param Affiliate $affiliate
     * @param Request   $request
     * @return RedirectResponse
     */
    public function update(Affiliate $affiliate, Request $request) : RedirectResponse
    {
        $request->validate(['name' => 'required']);
        $affiliate->update($request->all());
        return redirect()->to("/admin/affiliates")->with('message', $request->name . " updated.");
    }

    /**
     * Soft Delete Affiliate (so not to break anything)
     * @param Affiliate $affiliate
     * @return array
     */
    public function destroy(Affiliate $affiliate) : array
    {
        session()->flash('message', $affiliate->name . " has been removed.");
        $affiliate->delete();
        return ['callback' => "redirect:/admin/affiliates"];
    }

}
