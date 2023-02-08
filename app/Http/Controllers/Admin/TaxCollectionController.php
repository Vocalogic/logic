<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxLocation;
use Illuminate\View\View;

class TaxCollectionController extends Controller
{
    /**
     * Show taxes collected in location.
     * @param TaxLocation $taxLocation
     * @return View
     */
    public function index(TaxLocation $taxLocation) : View
    {
        return view('admin.tax_locations.tax_collections.index', ['taxLocation' => $taxLocation]);
    }

}
