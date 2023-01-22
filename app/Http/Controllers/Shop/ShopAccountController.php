<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Core\ActivityType;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountItem;
use App\Models\Invoice;
use App\Models\Order;
use App\Operations\Integrations\Merchant\Merchant;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ShopAccountController extends Controller
{

    /**
     * Show my account view.
     * @return View
     */
    public function index(): View
    {
        seo()
            ->title(user()->account->name . " Dashboard");
        return view('shop.account.index', ['account' => user()->account]);
    }

    /**
     * Show my services view.
     * @return View
     */
    public function services(): View
    {
        seo()
            ->title(user()->account->name . " Services");
        return view('shop.account.services.index', ['account' => user()->account]);
    }

    /**
     * Show All Invoices
     * @return View
     */
    public function invoices(): View
    {
        seo()
            ->title(user()->account->name . " Invoices");
        return view('shop.account.invoices.index', ['account' => user()->account]);
    }

    /**
     * Show invoice
     * @param Invoice $invoice
     * @return View
     */
    public function showInvoice(Invoice $invoice): View
    {
        if ($invoice->account_id != user()->account_id) abort(401);
        seo()
            ->title("Invoice #{$invoice->id}");
        return view('shop.account.invoices.show', ['invoice' => $invoice]);
    }

    /**
     * Show ``orders`` index.
     * @return View
     */
    public function orders(): View
    {
        seo()
            ->title(user()->account->name . " Orders");
        return view('shop.account.orders.index', ['account' => user()->account]);
    }

    /**
     * Show Order Status
     * @param string $hash
     * @return View
     */
    public function showOrder(string $hash): View
    {
        $order = Order::where('hash', $hash)->first();
        if (!$order || $order->account_id != user()->account_id) abort(401);
        seo()
            ->title("Order $order->hash");
        return view('shop.account.orders.show', ['order' => $order]);
    }

    /**
     * Show Pay Invoice Page
     * @param Invoice $invoice
     * @return View
     */
    public function pay(Invoice $invoice): View
    {
        if ($invoice->account_id != user()->account_id) abort(401);
        return view('shop.account.invoices.pay', ['invoice' => $invoice]);
    }

    /**
     * Update payment method
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveMethod(Request $request): RedirectResponse
    {
        // Pass this off to whatever class should handle it.
        $m = new Merchant();
        try
        {
            $m->addPaymentMethod(user()->account, $request);
            user()->account->update(['declined' => 0]);
            sysact(ActivityType::Account, user()->account->id, "added/updated their credit card information for Account");
        } catch (Exception $e)
        {
            user()->account->update(['declined' => 1]);
            return redirect()->to("/shop/account/profile")
                ->with('error', 'Transaction Declined: ' . $e->getMessage());
        }
        return redirect()->back();
    }

    /**
     * Download Invoice Copy
     * @param Invoice $invoice
     * @return mixed
     */
    public function downloadInvoice(Invoice $invoice): mixed
    {
        if ($invoice->account_id != user()->account_id) abort(401);
        return $invoice->pdf();
    }

    /**
     * Show account profile
     * @return View
     */
    public function profile(): View
    {
        seo()
            ->title(user()->account->name . " Profile");
        return view('shop.account.profile.index', ['account' => user()->account]);
    }

    /**
     * Show change password feature
     * @return View
     */
    public function changePassword(): View
    {
        seo()
            ->title("Change Password for " . user()->name);
        return view('shop.account.profile.password', ['account' => user()->account]);
    }

    /**
     * Update password
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password'  => 'required',
            'password2' => 'required'
        ]);
        if ($request->get('password') != $request->get('password2'))
        {
            throw new LogicException("Invalid Password Combination. Please try again.");
        }
        user()->update(['password' => bcrypt($request->get('password'))]);
        return redirect()->to("/shop/account");
    }

    /**
     * Show all users quotes.
     * @return View
     */
    public function quotes(): View
    {
        seo()->title("My Quotes");
        return view('shop.account.quotes.index', ['account' => user()->account]);
    }

    /**
     * Show quote and populate cart
     * @param string $qhash
     * @return View
     */
    public function showQuote(string $qhash): View
    {
        $account = user()->account;
        $quote = $account->quotes()->where('hash', $qhash)->first();
        if (!$quote) abort(404);
        if ($quote->archived) abort(404);
        return view('shop.presales.quote.index', ['account' => $account, 'quote' => $quote]);
    }

    /**
     * Show customer request termination modal
     * @param AccountItem $item
     * @return View
     */
    public function termModal(AccountItem $item): View
    {
        if ($item->account_id != user()->account->id) abort(404);
        return view('shop.account.services.term_modal', ['item' => $item]);
    }

    /**
     * Update Request Termination
     * @param AccountItem $item
     * @param Request     $request
     * @return RedirectResponse
     */
    public function termSave(AccountItem $item, Request $request): RedirectResponse
    {
        if ($item->account_id != user()->account->id) abort(404);
        $request->validate(['requested_termination_reason' => 'required']);
        $item->update([
            'requested_termination_reason' => $request->requested_termination_reason,
            'requested_termination_date'   => now()
        ]);
        sysact(ActivityType::RequestedTermination, $item->id, "requested service termination ($request->requested_termination_reason) for ");
        return redirect()->back();
    }

    /**
     * Reset Hash Access
     * @param string $hash
     * @return RedirectResponse
     */
    public function paymentForm(string $hash): RedirectResponse
    {
        $account = Account::where('cc_reset_hash', $hash)->first();
        if (!$account) abort(404);
        auth()->loginUsingId($account->admin->id);
        $account->update(['cc_reset_hash' => uniqid('RESET')]); // Only allow this once.
        return redirect()->to("/shop/account/profile");
    }
}
