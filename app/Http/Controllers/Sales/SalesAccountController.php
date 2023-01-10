<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\View\View;

class SalesAccountController extends Controller
{
    /**
     * Show accounts for agent
     * @return View
     */
    public function index(): View
    {
        $accounts = Account::where('agent_id', user()->id)->where('active', true)->orderBy('name')->get();
        return view('shop.sales.accounts.index', ['accounts' => $accounts]);
    }



}
