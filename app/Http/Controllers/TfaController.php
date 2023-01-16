<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class TfaController extends Controller
{
    public function index(): View
    {
        return view('2fa.index');
    }

}
