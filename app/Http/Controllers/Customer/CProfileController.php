<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Operations\Integrations\Merchant\Merchant;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CProfileController extends Controller
{
    /**
     * Show customer profile
     * @return View
     */
    public function index(): View
    {
        return view('customer.profile.index');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->address1)
        {
            $request->validate([
                'address1' => 'required',
                'address2' => 'nullable',
                'city'     => 'required',
                'state'    => 'required',
                'postcode' => 'required',
                'phone'    => 'nullable'
            ]);
            user()->account()->update([
                'address' => $request->address1,
                'address2' => $request->address2,
                'city'     => $request->city,
                'state'    => $request->state,
                'postcode' => $request->postcode,
                'phone'    => $request->phone
            ]);
        }
        // Update Credit Card
        if ($request->pmethod)
        {
            // Pass this off to whatever class should handle it.
            $m = new Merchant();
            try
            {
                $m->addPaymentMethod(user()->account, $request);
            } catch (Exception $e)
            {
                user()->account->update(['declined' => 1]);
                return redirect()->to("/c/profile?method=true")
                    ->with('error', 'Transaction Declined: ' . $e->getMessage());
            }
            user()->account->update(['declined' => 0]);
            return redirect()->back();
        }
        return redirect()->to("/");
    }

}
