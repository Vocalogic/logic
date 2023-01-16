<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class TransactionController extends Controller
{
    /**
     * Show Transaction Review Page
     * @return View
     */
    public function index(): View
    {
        return view('admin.transactions.index');
    }

}
