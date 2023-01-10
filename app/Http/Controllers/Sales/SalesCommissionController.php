<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\View\View;

class SalesCommissionController extends Controller
{
    /**
     * Show Agent Commissions
     * @return View
     */
    public function index(): View
    {
        $commissions = Commission::where('user_id', user()->id)->get();
        return view('shop.sales.commissions.index', ['commissions' => $commissions]);
    }

}
