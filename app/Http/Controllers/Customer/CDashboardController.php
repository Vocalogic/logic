<?php

namespace App\Http\Controllers\Customer;

use App\Enums\Core\ActivityType;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Operations\Integrations\Merchant\Merchant;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CDashboardController extends Controller
{

    /**
     * Show customer dashboard
     * @return View
     */
    public function index(): View
    {
        return view('customer.dashboard.index');
    }

    /**
     * Outside accessible payment update window.
     * @param string $hash
     * @return View
     */
    public function paymentForm(string $hash): View
    {
        $account = Account::where('cc_reset_hash', $hash)->first();
        if (!$account) abort(404);
        return view('customer.payment.index')->with('account', $account);
    }

    /**
     * Attempt to authorize the card.
     * @param string  $hash
     * @param Request $request
     * @return RedirectResponse
     */
    public function paymentSave(string $hash, Request $request) : RedirectResponse
    {
        // Pass this off to whatever class should handle it.
        $account = Account::where('cc_reset_hash', $hash)->first();
        if (!$account) abort(404);
        $m = new Merchant();
        try
        {
            $m->addPaymentMethod($account, $request);

        } catch (Exception $e)
        {
            $account->update(['declined' => 1]);
            return redirect()->back()
                ->with('error', 'Transaction Declined: ' . $e->getMessage());
        }
        $account->update(['declined' => 0]);
        sysact(ActivityType::Account, $account->id, "added a new Credit Card for ");
        return redirect()->back()->with('message', "Card successfully authorized. You can close this window.");
    }

    /**
     * Show welcome page with dashboard.
     * @return View
     */
    public function welcome(): View
    {
        return view('customer.dashboard.index')->with('welcome', true);
    }
}
