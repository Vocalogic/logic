<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\AlertType;
use App\Enums\Core\BillItemType;
use App\Enums\Core\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Activity;
use App\Models\BillItem;
use App\Models\HardwareOrder;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\LNPOrder;
use App\Operations\Core\AlertEngine;
use App\Operations\Shop\ShopBus;
use Illuminate\View\View;

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

    /**
     * Show admin feedback area.
     * @return View
     */
    public function feedback(): View
    {
        return view('admin.feedback.index');
    }

    /**
     * Show bug report area.
     * @return View
     */
    public function bugs(): View
    {
        return view('admin.feedback.bugs');
    }
}
