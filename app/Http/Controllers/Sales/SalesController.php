<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SalesController extends Controller
{
    /**
     * Show Sales Dashboard
     * @return View
     */
    public function index(): View
    {
        return view('shop.sales.index');
    }

}
