<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CInvoiceController extends Controller
{
    /**
     * Show customer invoices
     * @return View
     */
    public function index(): View
    {
        return view('customer.invoices.index');
    }

}
