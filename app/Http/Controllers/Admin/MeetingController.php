<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class MeetingController extends Controller
{

    /**
     * Show all meetings scheduled
     * @return View
     */
    public function index(): View
    {
        return view('admin.meetings.index');
    }

}
