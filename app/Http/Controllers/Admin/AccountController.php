<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\ACL;
use App\Enums\Core\ActivityType;
use App\Enums\Core\PaymentMethod;
use App\Enums\Files\FileType;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountAddon;
use App\Models\AccountItem;
use App\Models\AccountPricing;
use App\Models\AddonOption;
use App\Models\BillItem;
use App\Models\FileCategory;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\LOFile;
use App\Models\Quote;
use App\Models\User;
use App\Observers\AccountObserver;
use App\Operations\Core\LoFileHandler;
use App\Operations\Integrations\Accounting\Finance;
use App\Operations\Integrations\Merchant\Merchant;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
     * Show Account Profile
     * @param Account $account
     * @return RedirectResponse
     */
    public function show(Account $account): RedirectResponse
    {
        return redirect()->to("/admin/accounts/$account->id/overview");
    }


    /**
     * Show account overview page.
     * @param Account $account
     * @return View
     */
    public function overview(Account $account): View
    {
        return view('admin.accounts.overview.index', ['account' => $account]);
    }

    /**
     * Show active services
     * @param Account $account
     * @return View
     */
    public function services(Account $account): View
    {
        return view('admin.accounts.services.index', ['account' => $account]);
    }

    /**
     * Add an item to an account
     * @param Account  $account
     * @param BillItem $item
     * @return RedirectResponse
     */
    public function addItem(Account $account, BillItem $item): RedirectResponse
    {
        $accItem = $account->items()->create([
            'bill_item_id' => $item->id,
            'description'  => $item->description,
            'price'        => $account->getPreferredPricing($item),
            'qty'          => 1
        ]);
        _log($accItem, $item->name . " added to monthly services.");
        AccountObserver::$running = true; // Disable observer for next call.
        $account->update(['services_changed' => true]);
        return redirect()->to("/admin/accounts/$account->id/services")
            ->with('message', $item->name . " added to monthly services.");
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
        $old = $item->replicate();

        if (!$request->description)
        {
            $request->merge(['description' => $item->item->description]);
        }
        $accItem = $item->update([
            'price'           => convertMoney($request->price),
            'qty'             => $request->qty,
            'notes'           => $request->notes,
            'description'     => $request->description,
            'allowed_qty'     => $request->allowed_qty,
            'allowed_type'    => $request->allowed_type,
            'allowed_overage' => $request->allowed_overage,
            'frequency'       => $request->frequency
        ]);

        _log($accItem, $item->name . " updated.", $old);
        AccountObserver::$running = true; // Disable observer for next call.
        $account->update(['services_changed' => true]);
        if ($request->contract_quote_id)
        {
            $item->update(['quote_id' => $request->contract_quote_id]);
        }
        if ($request->contract_expires)
        {
            $item->quote->update(['contract_expires' => $request->contract_expires]);
        }
        return redirect()->to("/admin/accounts/$account->id/services")->with(['message' => $item->name . " Updated"]);
    }

    /**
     * Remove an item from an account.
     * @param Account     $account
     * @param AccountItem $item
     * @return string[]
     */
    public function delItem(Account $account, AccountItem $item): array
    {
        _log($item, $item->name . " removed from monthly services.");
        $item->delete();
        return ['callback' => 'reload'];
    }


    /**
     * Show Invoices for an Account
     * @param Account $account
     * @return View
     */
    public function invoices(Account $account): View
    {
        return view('admin.accounts.invoices.index', ['account' => $account]);
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
            'due_on' => now()->addDays($terms),
            'po'     => $request->po
        ]);
        _log($invoice, 'Invoice created');
        return redirect()->to("/admin/invoices/$invoice->id");
    }


    /**
     * Show users for an account.
     * @param Account $account
     * @return View
     */
    public function users(Account $account): View
    {
        return view('admin.accounts.users.index', ['account' => $account]);
    }

    /**
     * Show Quotes for an Account
     * @param Account $account
     * @return View
     */
    public function quotes(Account $account): View
    {
        return view('admin.accounts.quotes.index', ['account' => $account]);
    }

    /**
     * Show events for an account.
     * @param Account $account
     * @return View
     */
    public function events(Account $account): View
    {
        return view('admin.accounts.events.index', ['account' => $account]);
    }

    /**
     * Show billing settings for an account.
     * @param Account $account
     * @return View
     */
    public function billing(Account $account): View
    {
        return view('admin.accounts.billing.index', ['account' => $account]);
    }

    /**
     * Update billing details
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     */
    public function billingUpdate(Account $account, Request $request): RedirectResponse
    {
        $account->update($request->all());
        return redirect()->back()->with('message', 'Billing Settings Updated');
    }

    /**
     * Show account profile.
     * @param Account $account
     * @return View
     */
    public function profile(Account $account): View
    {
        return view('admin.accounts.profile.index', ['account' => $account]);
    }

    /**
     * Show pricing for an account
     * @param Account $account
     * @return View
     */
    public function pricing(Account $account): View
    {
        return view('admin.accounts.pricing.index', ['account' => $account]);
    }

    /**
     * Show files for an account.
     * @param Account $account
     * @return View
     */
    public function files(Account $account): View
    {
        return view('admin.accounts.files.index', ['account' => $account]);
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
        return redirect()->to("/admin/accounts/$account->id/files")->with('message', "File uploaded successfully.");
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
            'name'                => $request->name,
            'active'              => true,
            'payment_method'      => setting('invoices.default'),
            'agent_id'            => user()->id,
            'late_fee_percentage' => setting('invoices.lateFeePercentage')
        ]);
        $account->generateHash();
        $user->update(['account_id' => $account->id]);
        _log($account, 'Account created');
        return redirect()->to("/admin/accounts/$account->id/profile");
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
     * Update account properties.
     * @param Account $account
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Account $account, Request $request): RedirectResponse
    {
        $old = $account->replicate();
        $oldBill = $account->next_bill;
        if ($request->next_bill)
        {
            $request->merge([
                'next_bill' => Carbon::parse($request->next_bill),
                'bills_on'  => Carbon::parse($request->next_bill)->day,
            ]);
            if ($oldBill != $request->next_bill)
            {
                $account->update(['services_changed' => true]);
            }
            $account->update($request->all());
            session()->flash('message', "Billing Information Updated.");
            return redirect()->back();
        }

        $account->update($request->all());
        session()->flash("message", "Account Profile Updated.");
        // #45 - Try to get a favicon from the website if there is no image and a website exists.
        if ($request->website && !$account->logo_id)
        {
            $account->getFavIcon();
        }
        _log($account, "Account Updated", $old);
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
        if (!$request->hasFile('logo'))
        {
            throw new LogicException("You must select a logo to upload.");
        }
        $lo = new LoFileHandler();
        $file = $lo->createFromRequest($request, 'logo', FileType::Image, $account->id);
        $lo->unlock($file);
        $account->update(['logo_id' => $file->id]);
        return redirect()->to("/admin/accounts/$account->id");
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
            return redirect()->to("/admin/accounts/$account->id/profile")
                ->with('error', 'Transaction Declined: ' . $e->getMessage());
        }
        $account->update(['declined' => 0]);
        return redirect()->back();
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
                        'price'                => $request->get("price_$key") ? convertMoney($request->get("price_$key")) : $price,
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
                        'price'                => $request->get("price_$key") ? convertMoney($request->get("price_$key")) : $price,
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
        return redirect()->to("/admin/accounts/{$item->account->id}/overview");
    }

    /**
     * Import New Account List from CSV
     * @return View
     */
    public function importModal(): View
    {
        return view('admin.accounts.import_modal');
    }

    /**
     * Modal to add a product or service to special pricing.
     * @param Account $account
     * @param string  $type
     * @return View
     */
    public function pricingModal(Account $account, string $type): View
    {
        return view('admin.accounts.pricing.add', ['account' => $account, 'type' => $type]);
    }

    /**
     * Add bill item to special pricing
     * @param Account  $account
     * @param BillItem $item
     * @return RedirectResponse
     */
    public function pricingApply(Account $account, BillItem $item): RedirectResponse
    {
        $account->pricings()->create([
            'bill_item_id'   => $item->id,
            'price'          => $item->type == 'services' ? $item->mrc : $item->nrc,
            'price_children' => $item->type == 'services' ? $item->mrc : $item->nrc,
        ]);
        return redirect()->back()->with('message', $item->name . " Added for Special Pricing");
    }

    /**
     * X-editable updater for pricing.
     * @param Account        $account
     * @param AccountPricing $item
     * @param Request        $request
     * @return true[]
     */
    public function pricingUpdate(Account $account, AccountPricing $item, Request $request): array
    {
        $item->update([$request->name => convertMoney($request->value)]);
        return ['success' => true];
    }

    /**
     * Remove special pricing entry.
     * @param Account        $account
     * @param AccountPricing $item
     * @return string[]
     */
    public function pricingRemove(Account $account, AccountPricing $item): array
    {
        session()->flash('message', $item->item->name . " removed from special pricing.");
        $item->delete();
        return ['callback' => 'reload'];
    }

    /**
     * Import Leads
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function import(Request $request): RedirectResponse
    {
        if (!$request->hasFile('import_file'))
        {
            throw new LogicException("You must attach a CSV for importing.");
        }
        $file = fopen($request->file('import_file')->getRealPath(), 'r');
        $count = 0;
        while (($line = fgetcsv($file)) !== false)
        {
            if ($line[0] == 'Company Name') continue;                // Ignore Headers
            if (Account::where('name', $line[0])->count()) continue; // Don't duplicate
            if (User::where('email', $line[3])->count()) continue;   // Don't duplicate user either.
            if (!isset($line[1]) || !$line[1]) continue;
            if (!isset($line[9]) || !$line[9]) continue; // Make sure we have all fields
            // Create Account
            $account = Account::create([
                'name'     => $line[0],
                'active'   => true,
                'agent_id' => 1,
                'address'  => $line[5],
                'address2' => $line[6],
                'city'     => $line[7],
                'state'    => $line[8],
                'postcode' => $line[9],
                'website'  => $line[4],
                'hash'     => uniqid('A-')
            ]);
            User::create([
                'name'       => $line[1],
                'email'      => $line[3],
                'acl'        => ACL::ADMIN->value,
                'account_id' => $account->id,
                'password'   => uniqid()
            ]);
            $count++;
        }
        return redirect()->back()->with('message', $count . " Accounts Imported Successfully");
    }
}

