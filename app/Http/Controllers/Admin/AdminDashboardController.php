<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HardwareOrder;
use App\Operations\Core\AlertEngine;
use Illuminate\Contracts\View\View;


class AdminDashboardController extends Controller
{

    /**
     * Build a list of dashboard alerts.
     * @return array
     */
    public function alerts(): array
    {
        return AlertEngine::run();
    }

    /**
     * Show admin dashboard.
     * @return View
     */
    public function index(): View
    {
        return view('admin.dashboard.index')->with('alerts', $this->alerts());
    }
}
