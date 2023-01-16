<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Operations\Core\CashFlow;
use Illuminate\Contracts\View\View;

class CashFlowController extends Controller
{
    /**
     * Show cash flow report
     */
    public function index(): View
    {
        $year1 = now()->year;
        $year2 = now()->addYear()->year;

        $cf = new CashFlow();
        $cf->setYear($year1);
        $cf->init();
        $cf->iterateYear();

        $cf2 = new CashFlow();
        $cf2->setYear($year2);
        $cf2->init();
        $cf2->iterateYear();


        return view('admin.finance.flow.index')->with([
            'data1' => $cf->breakdown,
            'data2' => $cf2->breakdown,
            'y1'    => $year1,
            'y2'    => $year2
        ]);
    }

}
