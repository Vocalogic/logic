<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadType;
use App\Models\Term;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TermController extends Controller
{

    /**
     * Show Terms
     * @return View
     */
    public function index(): View
    {
        foreach (LeadType::all() as $type)
        {
            if (!$type->term)
            {
                $type->term()->create(['name' => $type->name . " Terms of Service"]);
            }
        }
        return view('admin.terms.index');
    }

    /**
     * Show Terms Editor
     * @param Term $term
     * @return View
     */
    public function show(Term $term) : View
    {
        return view('admin.terms.show')->with('term', $term);
    }

    /**
     * Update Terms of Service
     * @param Term    $term
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Term $term, Request $request): RedirectResponse
    {
        $term->update($request->all());
        return redirect()->to("/admin/terms")->with('message', "Terms of Service Updated");
    }


}
