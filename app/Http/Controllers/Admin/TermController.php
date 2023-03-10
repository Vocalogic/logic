<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillItem;
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
        return view('admin.terms.index');
    }

    /**
     * @return View
     */
    public function create(): view
    {
        return view('admin.terms.show')->with('term', new Term);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required', 'body' => 'required']);
        (new Term)->create([
            'name' => $request->name,
            'body' => $request->body,
        ]);

        return redirect()->to("/admin/terms")->with('message', "Terms of Service Saved");
    }

    /**
     * Show Terms Editor
     * @param Term $term
     * @return View
     */
    public function show(Term $term): View
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

    /**
     * @param Term $term
     * @return array
     */
    public function destroy(Term $term): array
    {
        BillItem::where('tos_id', $term->id)->update(['tos_id' => null]);
        $term->delete();
        session()->flash('message', "Terms of Service Deleted");
        return ['callback' => "redirect:/admin/terms"];
    }


}
