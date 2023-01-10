<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadType;
use App\Models\Term;
use Illuminate\View\View;

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


}
