<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CServiceController extends Controller
{
    /**
     * Show customer services
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        if ($request->download)
        {
            return user()->account->statement();
        }
        return view('customer.services.index');
    }
}
