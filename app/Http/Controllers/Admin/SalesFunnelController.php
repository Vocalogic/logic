<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SalesFunnelController extends Controller
{
    /**
     * Show sales funnel.
     * @return View
     */
    public function index(): View
    {
        return view('admin.sales.funnel.index');
    }

}
