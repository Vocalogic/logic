<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\AccountFileType;
use App\Enums\Core\ACL;
use App\Enums\Core\ActivityType;
use App\Enums\Core\PaymentMethod;
use App\Enums\Files\FileType;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountAddon;
use App\Models\AccountItem;
use App\Models\Activity;
use App\Models\AddonOption;
use App\Models\BillItem;
use App\Models\FileCategory;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\LNPOrder;
use App\Models\LOFile;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\User;
use App\Operations\API\NS\Domain;
use App\Operations\Core\LoFileHandler;
use App\Operations\Integrations\Accounting\Finance;
use App\Operations\Integrations\Merchant\Merchant;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{

    /**
     * Show all active accounts.
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        if ($request->show == 'mrr')
        {
            $accounts = Account::where('id', '>', 1)
                ->where('active', 1)
                ->has('items')
                ->get();
        }
        elseif ($request->show == 'nrc')
        {
            $accounts = Account::where('id', '>', 1)->where('active', 1)->doesntHave('items')->get();

        }
        elseif ($request->show == 'inactive')
        {
            $accounts = Account::where('id', '>', 1)->where('active', 0)->get();

        }
        else
        {
            $accounts = Account::where('id', '>', 1)->where('active', 1)->get();
        }

        return view('admin.accounts.index', ['accounts' => $accounts]);
    }

    /**
     * Show create modal
     * @return View
     */
    public function create(): View
    {
        return view('admin.accounts.create_modal');
    }

    /**
     * Show Quote inside Tab
     * @param Account $account
     * @param Quote   $quote
     * @return RedirectResponse
     */
    public function showQuote(Account $account, Quote $quote): RedirectResponse
    {
        return redirect()->to("/admin/accounts/$account->id?active=quotes&quote=$quote->id");
    }

    /**
     * Show an invoice redirect.
     * @param Account $account
     * @param Invoice $invoice
     * @return RedirectResponse
     */
    public function showInvoice(Account $account, Invoice $invoice): RedirectResponse
    {
        return redirect()->to("/admin/accounts/$account->id?active=invoices&invoice=$invoice->id");
    }


    /**
     * Show Account Profile
     * @param Account $account
     * @param Request $request
     * @return View
     */
    public function show(Account $account, Request $request): View
    {
        if ($request->sync)
        {
            // Sync account and then redirect.
            Finance::syncAccount($account);
        }
        $tabs = (object)[
            'overview' => false,
            'services' => false,
            'invoices' => false,
            'quotes'   => false,
            'orders'   => false,
            'profile'  => false,
            'pricing'  => false,
            'files'    => false,
            'pbx'      => false,
            'events'   => false,
            'partner'  => false,
            'users'    => false
        ];
        if ($request->active)
        {
            $tabs->{$request->active} = true;
        }
        else $tabs->overview = true;
        return view('admin.accounts.show')->with('account', $account)->with('tab', $tabs);
    }

    /**
     * Manual creation of an account.
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function store(Request $request): RedirectResponse
    {
        if (User::where('email', $request->email)->count())
        {
            throw new LogicException("An account with this email already exists.");
        }
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL))
        {
            throw new LogicException("A valid email address is required.");
        }
        $request->validate([
            'name'    => 'required',
            'contact' => 'required',
            'email'   => "required"
        ]);
        $user = User::create([
            'name'       => $request->contact,
            'email'      => $request->email,
            'password'   => bcrypt("PW" . mt_rand(2, 552312)),
            'acl'        => ACL::ADMIN->value,
            'account_id' => 0
        ]);

        $account = Account::create([
            'name'           => $request->name,
            'active'         => true,
            'payment_method' => setting('invoices.default'),
            'agent_id'       => user()->id
        ]);
        $account->generateHash();
        $user->update(['account_id' => $account->id]);
        return redirect()->to("/admin/accounts/$account->id?active=profile");
    }

    /**
     * Show edit item modal
     * @param Account     $account
     * @param AccountItem $item
     * @return View
     */
    public function editItem(Account $account, AccountItem $item): View
    {
        return view('admin.accounts.services.service_modal')->with('item', $item);
    }

    /**
     * Add an item to an account
     * @param Account  $account
     * @param BillItem $item
     * @return RedirectResponse
     */
    public function addItem(Account $account, BillItem $item): RedirectResponse
    {
        $account->items()->create([
            'bill_item_id' => $item->id,
            'description'  => $item->description,
            'price'        => $item->mrc,
            'qty'          => 1
        ]);
        $account->update(['services_changed' => true]);
        return redirect()->to("/admin/accounts/$account->id?active=services");
    }

    /**
     * Update a service item.
     * @param Account     $account
     * @param AccountItem $item
     * @param Request     $request
     * @return RedirectResponse
     */
    public function updateItem(Account $account, AccountItem $item, Request $request): RedirectResponse
    {
        $item->update([
            'price'           => convertMoney($request->price),
            'qty'             => $request->qty,
            'notes'           => $request->notes,
            'description'     => $request->description,
            'allowed_qty'     => $request->allowed_qty,
            'allowed_type'    => $request->allowed_type,
            'allowed_overage' => $request->allowed_overage,
            'frequency'       => $request->frequency
        ]);
        $account->update(['services_changed' => true]);
        if ($request->contract_quote_id)
        {
            $item->update(['quote_id' => $request->contract_quote_id]);
        }
        if ($request->contract_expires)
        {
            $item->quote->update(['contract_expires' => $request->contract_expires]);
        }
        return redirect()->to("/admin/accounts/$account->id?active=services");
    }

    /**
     * Remove an item from an account.
     * @param Account     $account
     * @param AccountItem $item
     * @return string[]
     */
    public function delItem(Account $account, AccountItem $item): array
    {
        $item->delete();
        return ['callback' => 'reload'];
    }

    /**
     * Update account properties.
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Account $account, Request $request): RedirectResponse
    {
        $oldBill = $account->next_bill;
        if ($request->next_bill)
        {
            $request->merge([
                'next_bill' => Carbon::parse($request->next_bill),
                'bills_on'  => Carbon::parse($request->next_bill)->day,
            ]);
        }
        if ($oldBill != $request->next_bill)
        {
            $account->update(['services_changed' => true]);
        }
        $account->update($request->all());
        // #45 - Try to get a favicon from the website if there is no image and a website exists.
        if ($request->website && !$account->logo_id)
        {
            $account->getFavIcon();
        }

        return redirect()->back();
    }

    /**
     * Update company logo
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function updateLogo(Account $account, Request $request): RedirectResponse
    {
        $lo = new LoFileHandler();
        $file = $lo->createFromRequest($request, 'logo', FileType::Image, $account->id);
        $lo->unlock($file);
        $account->update(['logo_id' => $file->id]);
        return redirect()->to("/admin/accounts/$account->id");
    }

    /**
     * PBX Assignment Form
     * @param Account $account
     * @param Request $request
     * @param Account $account
     * @param Request $request
     * @param Account $account
     * @param Request $request
     * @return View
     *
     * public function pbxAssignForm(Account $account, Request $request): View
     * {
     * return view('voip::admin.accounts.pbx.assign')->with('account', $account);
     * }
     *
     * /**
     * Assign or create pbx
     * @return RedirectResponse
     * @return RedirectResponse
     * @throws GuzzleException
     *
     * public function pbxAssign(Account $account, Request $request): RedirectResponse
     * {
     * if ($request->domain)
     * {
     * // Just assign
     * $account->update(['pbx_domain' => $request->domain]);
     * }
     * else
     * {
     * // Create new Domain
     * $dom = sprintf("D%d", mt_rand(100000, 999999));
     * $domain = new Domain($account->provider);
     * $domain->create($dom, $request->newDomain);
     * $account->update(['pbx_domain' => sprintf("%s.%s", $dom, $account->provider->territory)]);
     * }
     * return redirect()->back();
     * }
     *
     * /**
     * Create new Quote
     */
    public function storeQuote(Account $account, Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required']);
        $quote = $account->quotes()->create([
            'name'       => $request->name,
            'hash'       => "QO-" . uniqid(),
            'preferred'  => 1,
            'net_terms'  => $account->net_terms,
            'expires_on' => now()->addDays((int)setting('quotes.length'))
        ]);
        return redirect()->to("/admin/accounts/$account->id/quotes/$quote->id");
    }

    /**
     * Refresh the Account Stats
     * @param Account $account
     * @return RedirectResponse
     * @throws GuzzleException
     */
    public function pbxRefresh(Account $account): RedirectResponse
    {
        $account->getPBXStats(true);
        return redirect()->to("/admin/accounts/$account->id?active=pbx");
    }

    /**
     * Upload a file into an account.
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function uploadFile(Account $account, Request $request): RedirectResponse
    {
        $request->validate(['description' => 'required']);
        $lo = new LoFileHandler();
        $category = FileCategory::find($request->file_category_id);
        if (!$request->hasFile('uploaded')) throw new LogicException("No file found to process.");
        $correlatedType = $category->type->getRootType();
        $file = $lo->createFromRequest($request, 'uploaded', $correlatedType, $account->id);
        $file->update([
            'meta'             => $category->type->value,
            'account_id'       => $account->id,
            'description'      => $request->description,
            'auth_required'    => $request->public ? 0 : 1,
            'file_category_id' => $category->id
        ]);

        return redirect()->to("/admin/accounts/$account->id?active=files");
    }

    /**
     * Delete a file
     * @param Account $account
     * @param LOFile  $file
     * @param Request $request
     * @return string[]
     */
    public function deleteFile(Account $account, LOFile $file, Request $request): array
    {
        $handle = new LoFileHandler();
        $handle->delete($file->id);
        return ['callback' => 'reload'];
    }

    /**
     * Create a new invoice and return to builder.
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeInvoice(Account $account, Request $request): RedirectResponse
    {
        $terms = $request->terms ?: $account->net_terms;
        $invoice = $account->invoices()->create([
            'due_on' => now()->addDays($terms)
        ]);
        return redirect()->to("/admin/accounts/$account->id/invoices/$invoice->id");
    }

    /**
     * Add a new payment method
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     */
    public function addPaymentMethod(Account $account, Request $request): RedirectResponse
    {
        // Pass this off to whatever class should handle it.
        $m = new Merchant();
        try
        {
            $m->addPaymentMethod($account, $request);
        } catch (Exception $e)
        {
            $account->update(['declined' => 1]);
            return redirect()->to("/admin/accounts/$account->id?active=profile")
                ->with('error', 'Transaction Declined: ' . $e->getMessage());
        }
        $account->update(['declined' => 0]);
        return redirect()->back();
    }

    /**
     * Authorize or Apply a payment
     * @param Account $account
     * @param Invoice $invoice
     * @param Request $request
     * @return RedirectResponse
     */
    public function authPayment(Account $account, Invoice $invoice, Request $request): RedirectResponse
    {
        $amount = convertMoney($request->amount); // Remove commas for amount
        if (!$request->pmethod)
        {
            session()->flash('error', "No payment method was selected. Please try again with a payment method.");
            return redirect()->back();
        }
        if ($amount <= 0)
        {
            session()->flash('error', "You must enter a positive amount for a payment amount.");
            return redirect()->back();
        }

        if ($amount > $invoice->balance)
        {
            session()->flash('error', "You cannot charge more than the balance of the invoice. ($amount)");
            return redirect()->back();
        }

        try
        {
            $method = PaymentMethod::from($request->pmethod);
            $invoice->processPayment($method, $amount, $request->details);
            $account->update(['declined' => 0]);
        } catch (LogicException $e)
        {
            session()->flash('error', "Unable to process payment: " . $e->getMessage());
            return redirect()->back();
        }
        session()->flash('message',
            "A payment of $" . moneyFormat($amount) . " was applied to Invoice #$invoice->id.");
        return redirect()->to("/admin/accounts/$account->id?active=invoices");
    }


    /**
     * Enable partner controls for an account
     * @param Account $account
     * @return array
     */
    public function enablePartner(Account $account): array
    {
        $account->update(['is_partner' => true]);
        return ['callback' => "redirect:/admin/accounts/$account->id?active=partner"];
    }

    /**
     * Download Account Statement
     * @param Account $account
     * @return mixed
     */
    public function statement(Account $account): mixed
    {
        return $account->statement();
    }

    /**
     * Show Invoice Item Edit Modal
     * @param Account     $account
     * @param Invoice     $invoice
     * @param InvoiceItem $item
     * @return View
     */
    public function showItem(Account $account, Invoice $invoice, InvoiceItem $item): View
    {
        return view('admin.accounts.invoices.item')->with('invoice', $invoice)->with('item', $item);
    }

    /**
     * Update an invoice item
     * @param Account     $account
     * @param Invoice     $invoice
     * @param InvoiceItem $item
     * @param Request     $request
     * @return RedirectResponse
     */
    public function updateInvoiceItem(
        Account $account,
        Invoice $invoice,
        InvoiceItem $item,
        Request $request
    ): RedirectResponse {
        $request->validate([
            'price' => 'required|numeric',
            'qty'   => 'required|numeric'
        ]);
        if (!$request->description)
        {
            $request->merge(['description' => '']);
        }
        $item->update([
            'price'       => convertMoney($request->price),
            'qty'         => $request->qty,
            'description' => $request->description
        ]);
        return redirect()->back();
    }

    /**
     * Send email template to account owner or billing contact
     * @param Account $account
     * @return array
     */
    public function paymentRequest(Account $account): array
    {
        $account->sendBillingEmail('account.cardrequest', [$account], []);
        return ['callback' => 'reload'];
    }

    /**
     * Update S3 Credentials for an account.
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateS3(Account $account, Request $request): RedirectResponse
    {
        $account->update($request->all());
        return redirect()->back();
    }

    /**
     * Show addons modal.
     * @param Account     $account
     * @param AccountItem $item
     * @return View
     */
    public function addons(Account $account, AccountItem $item): View
    {
        return view('admin.accounts.services.addons')->with(['account' => $account, 'item' => $item]);
    }

    /**
     * Remove an addon.
     * @param Account      $account
     * @param AccountItem  $item
     * @param AccountAddon $addon
     * @return string[]
     */
    public function removeAddon(Account $account, AccountItem $item, AccountAddon $addon): array
    {
        $addon->delete();
        return ['callback' => 'reload'];
    }

    /**
     * Save addons for a particular quote item.
     * @param Account     $account
     * @param AccountItem $item
     * @param Request     $request
     * @return RedirectResponse
     */
    public function saveAddons(Account $account, AccountItem $item, Request $request): RedirectResponse
    {
        foreach ($request->all() as $key => $val)
        {
            if (preg_match("/add\_/i", $key))
            {
                $x = explode("add_", $key);
                $key = $x[1];
                // $key is the addon id. val is our option we selected.
                $q = AccountAddon::where('addon_id', $key)->where('account_bill_item_id', $item->id)->first();
                $oitem = AddonOption::find($val);
                if (!$oitem) continue;
                $price = $oitem->price ?: 0;
                if (!$q)
                {
                    AccountAddon::create([
                        'addon_id'             => $key,
                        'account_bill_item_id' => $item->id,
                        'addon_option_id'      => $val,
                        'price'                => $request->get("price_$key") ?: $price,
                        'qty'                  => $request->get("qty_$key") ?: 1,
                        'name'                 => $oitem->name,
                        'account_id'           => $account->id
                    ]);
                }
                else
                {
                    $q->update([
                        'addon_id'             => $key,
                        'account_bill_item_id' => $item->id,
                        'addon_option_id'      => $val,
                        'price'                => $request->get("price_$key") ?: $price,
                        'qty'                  => $request->get("qty_$key") ?: 1,
                        'name'                 => $oitem->name,
                        'account_id'           => $account->id
                    ]);
                }
            } // if match on add_
        }
        return redirect()->back()->with('message', 'Addons Applied Successfully');
    }

    /**
     * Show cancel account modal
     * @param Account $account
     * @return View
     */
    public function cancelAccountModal(Account $account): View
    {
        return view('admin.accounts.cancel')->with('account', $account);
    }

    /**
     * Cancel Account action
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     */
    public function cancelAccount(Account $account, Request $request): RedirectResponse
    {
        sysact(ActivityType::Account, $account->id, "cancelled account ($request->reason)");
        $account->update([
            'cancelled_on'  => now(),
            'cancel_reason' => $request->reason,
            'active'        => 0
        ]);
        return redirect()->to("/admin/accounts");
    }

    /**
     * Redirect invoice link
     * @param Invoice $invoice
     * @return RedirectResponse
     */
    public function invoiceRedirect(Invoice $invoice): RedirectResponse
    {
        return redirect()->to("/admin/accounts/$invoice->account_id?active=invoices&invoice=$invoice->id");
    }

    /**
     * Show suspension Modal
     * @param Account $account
     * @return View
     */
    public function suspendModal(Account $account): View
    {
        return view('admin.accounts.suspend_modal', ['account' => $account]);
    }

    /**
     * Schedule Suspension of Services
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     */
    public function scheduleSuspend(Account $account, Request $request): RedirectResponse
    {
        $request->validate(['reason' => 'required']);
        foreach ($request->all() as $key => $val)
        {
            if (!preg_match("/s_/i", $key)) continue;
            $x = explode("s_", $key);
            $key = $x[1];
            if ($val)
            {
                $account->items()->where('id', $key)->update([
                    'suspend_on'     => Carbon::parse($val),
                    'suspend_reason' => $request->reason
                ]);
            }
        }
        $account->sendSuspensionNotice();
        return redirect()->back()->with(['message' => "Suspension Notice Sent"]);
    }

    /**
     * Show termination scheduler
     * @param Account $account
     * @return View
     */
    public function terminateModal(Account $account): View
    {
        return view('admin.accounts.terminate_modal', ['account' => $account]);
    }

    /**
     * Schedule Termination of Services
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     */
    public function scheduleTerminate(Account $account, Request $request): RedirectResponse
    {
        $request->validate(['reason' => 'required']);
        foreach ($request->all() as $key => $val)
        {
            if (!preg_match("/s_/i", $key)) continue;
            $x = explode("s_", $key);
            $key = $x[1];
            if ($val)
            {
                $account->items()->where('id', $key)->update([
                    'terminate_on'     => Carbon::parse($val),
                    'terminate_reason' => $request->reason,
                    'suspend_reason'   => null,
                    'suspend_on'       => null
                ]);
            }
        }
        $account->sendTerminationNotice();
        return redirect()->back()->with(['message' => "Termination Notice Sent"]);
    }

    /**
     * Immediately send suspension notice
     * @param Account     $account
     * @param AccountItem $item
     * @return array
     */
    public function suspendItem(Account $account, AccountItem $item): array
    {
        $item->update(['suspend_on' => null, 'suspend_reason' => null]);
        $item->sendImmediateSuspension();
        return ['callback' => 'reload'];
    }

    /**
     * Immediately send termination notice for item.
     * @param Account     $account
     * @param AccountItem $item
     * @return array
     */
    public function terminateItem(Account $account, AccountItem $item): array
    {
        $item->update(['terminate_on' => null, 'terminate_reason' => null]);
        $item->sendImmediateTermination();
        return ['callback' => 'reload'];
    }

    /**
     * Send Notification
     * @param Account $account
     * @return RedirectResponse
     */
    public function notifyServices(Account $account): RedirectResponse
    {
        $account->sendServiceUpdateNotification();

        return redirect()->back()->with('message', "Services Changed notification sent successfully.");
    }

    /**
     * Clear updated services notification
     * @param Account $account
     * @return RedirectResponse
     */
    public function clearServices(Account $account): RedirectResponse
    {
        $account->update(['services_changed' => false]);
        return redirect()->back();
    }

    /**
     * Remove Notice
     * @param Account     $account
     * @param AccountItem $item
     * @param string      $type
     * @return string[]
     */
    public function removeNotice(Account $account, AccountItem $item, string $type): array
    {
        switch ($type)
        {
            case 'suspension' :
                $item->update(['suspend_on' => null, 'suspend_reason' => null]);
                break;
            case 'termination' :
                $item->update(['terminate_on' => null, 'terminate_reason' => null]);
        }
        return ['callback' => 'reload'];
    }

    /**
     * Show ACH Modal
     * @param Account $account
     * @return View
     */
    public function achModal(Account $account): View
    {
        return view('admin.accounts.ach_modal', ['account' => $account]);
    }

    /**
     * Save ACH Payment Method
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveACH(Account $account, Request $request): RedirectResponse
    {
        $request->validate([
            'routing' => 'size:9',
            'account' => 'required'
        ]);
        $account->update([
            'merchant_payment_token' => '',
            'merchant_ach_aba'       => $request->routing,
            'merchant_ach_account'   => $request->account
        ]);
        return redirect()->back()->with('message', 'ACH Payment Details Updated');
    }


    /**
     * Show requirement metadata modal
     * @param Account     $account
     * @param AccountItem $item
     * @return View
     */
    public function showMeta(Account $account, AccountItem $item): View
    {
        return view('admin.accounts.services.requirements', ['account' => $account, 'item' => $item]);
    }

    /**
     * Save Account Item Metadata
     * @param Account     $account
     * @param AccountItem $item
     * @param Request     $request
     * @return RedirectResponse
     */
    public function saveMeta(Account $account, AccountItem $item, Request $request): RedirectResponse
    {
        foreach ($request->all() as $key => $val)
        {
            if (preg_match("/a_/i", $key))
            {
                $x = explode("_", $key); // key could be a_3 or a_3_1
                if (!isset($x[2]))
                {
                    $item->updateMeta((int)$x[1], $val);
                }
                else
                {
                    $item->updateMeta((int)$x[1], $val, (int)$x[2]);
                }
            }
        }
        return redirect()->back()->with('message', "Requirements saved successfully.");
    }

    /**
     * Activity Redirector
     * @param AccountItem $item
     * @return RedirectResponse
     */
    public function redirectItem(AccountItem $item): RedirectResponse
    {
        return redirect()->to("/admin/accounts/{$item->account->id}?active=services");
    }

}

