<?php

namespace App\Models;

use App\Enums\Core\AccountFileType;
use App\Enums\Core\ACL;
use App\Enums\Core\ActivityType;
use App\Enums\Core\BillFrequency;
use App\Enums\Core\IntegrationType;
use App\Enums\Core\InvoiceStatus;
use App\Enums\Core\LNPStatus;
use App\Enums\Core\MetricType;
use App\Enums\Core\PaymentMethod;
use App\Enums\Files\FileType;
use App\Exceptions\LogicException;
use App\Jobs\ImageDiscoveryJob;
use App\Operations\Admin\AnalysisEngine;
use App\Operations\API\NS\CDR;
use App\Operations\API\NS\Domain;
use App\Operations\API\NS\NSUser;
use App\Operations\Core\BillingEngine;
use App\Operations\Core\LoFileHandler;
use App\Operations\Core\MakePDF;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use App\Traits\HasLogTrait;

/**
 * @property mixed         $admin
 * @property mixed         $id
 * @property mixed         $items
 * @property mixed         $postcode
 * @property mixed         $city
 * @property mixed         $state
 * @property mixed         $pbx_domain
 * @property mixed         $next_bill
 * @property PaymentMethod $payment_method
 * @property mixed         $card_details
 * @property mixed         $net_terms
 * @property mixed         $provider
 * @property mixed         $bills_on
 * @property mixed         $merchant_account_id
 * @property mixed         $name
 * @property mixed         $address
 * @property mixed         $address2
 * @property mixed         $country
 * @property mixed         $merchant_payment_token
 * @property mixed         $credit_balance
 * @property mixed         $merchant_payment_last4
 * @property mixed         $support_organization_id
 * @property mixed         $billing_email
 * @property mixed         $declined
 * @property mixed         $finance_customer_id
 * @property mixed         $cc_reset_hash
 * @property mixed         $account_credit
 * @property mixed         $website
 * @property mixed         $net_days
 * @property mixed         $mrr
 * @property mixed         $invoices
 * @property mixed         $merchant_ach_aba
 * @property mixed         $merchant_ach_account
 * @property mixed         $partner
 * @property mixed         $partner_id
 * @property mixed         $commissionable
 * @property mixed         $quotes
 * @property mixed         $agent_id
 * @property mixed         $parent
 * @property mixed         $taxable
 */
class Account extends Model
{
    use HasLogTrait;

    protected $guarded = ['id'];
    public    $dates   = ['next_bill'];
    public    $casts   = [
        'payment_method'    => PaymentMethod::class,
        'merchant_metadata' => 'json'
    ];

    public $attributes = [
      'mrr', 'account_balance'
    ];

    /**
     * When showing the log entries for an account, we want to
     * add the item logs as well.
     * @var array|string[]
     */
    public array $logRelationships = ['items'];

    /**
     * Define our array of tracked changes. This will be used for the
     * logging class to optional compare a previous instance of an
     * object before it was changed and print human readable changes.
     * @var array
     */
    public array $tracked = [
        'name'                  => "Account Name",
        'address'               => "Address",
        'address2'              => "Address Line 2",
        'city'                  => "City",
        'state'                 => "State",
        'postcode'              => "Zip/Postal Code",
        'active'                => "Active State",
        'agent_id'              => "Sales Agent|agent.name", // use | for a relationship on the model dotted and field
        'next_bill'             => "Next Bill Date",
        'bills_on'              => "Billing Day",
        'billing_email'         => "Billing Email",
        'auto_bill'             => "Automatic Billing",
        'is_commissionable'     => "Commissionable Mode|bool",
        'taxable'               => "Customer Tax Mode",
        'account_credit'        => "Account Credit Balance",
        'account_credit_reason' => "Reason for Credit",
        'impose_late_fee'       => "Late Fee Assessment|bool",
        'late_fee_percentage'   => "Late Fee Percentage"
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get admin user
     * @return HasOne
     */
    public function admin(): HasOne
    {
        return $this->hasOne(User::class)->where('acl', ACL::ADMIN);
    }

    /**
     * An account has many quotes.
     * @return HasMany
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * An account is assigned to a provider.
     * @return BelongsTo
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * The agent that is primary on the account.
     * @return BelongsTo
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * An account has many bill items
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(AccountItem::class);
    }

    /**
     * Accounts can have many product item overrides.
     * @return HasMany
     */
    public function overrides(): HasMany
    {
        return $this->hasMany(AccountItem::class, 'bill_item_id');
    }

    /**
     * An account has many invoices.
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * An account has many orders.
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * An account has one provisioning order for a pbx.
     * @return HasOne
     */
    public function provisioning(): HasOne
    {
        return $this->hasOne(Provisioning::class);
    }

    /**
     * An account can be tied to another partner.
     * @return BelongsTo
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * An account can have many commission entries
     * @return HasMany
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * An account has many call recordings.
     * @return HasMany
     */
    public function recordings(): HasMany
    {
        return $this->hasMany(CallRecording::class);
    }

    /**
     * If an account is parented to another account.
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Accounts can have children parented to another account.
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    /**
     * An account can have many special pricing entries.
     * @return HasMany
     */
    public function pricings(): HasMany
    {
        return $this->hasMany(AccountPricing::class);
    }

    /**
     * An account can have an affiliate for commissions (via coupon)
     * @return BelongsTo
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Fire off the welcome email template to the admin.
     * @return void
     */
    public function sendWelcomeEmail(): void
    {
        template('account.welcome', $this->admin, [$this]);
    }

    /**
     * Get MRR Commissionable for Agent
     * @return float
     */
    public function getCommissionableAttribute(): float
    {
        $agent = User::find($this->agent_id);
        if (!$agent) return 0;
        if (!$agent->agent_comm_mrc) return 0;
        $amt = $agent->agent_comm_mrc / 100;
        return $this->mrr * $amt;
    }

    /**
     * Get the current MRR on an account.
     * @return int
     */
    public function getMrrAttribute(): int
    {
        $total = 0;
        foreach ($this->items as $item)
        {
            if ($item->frequency && $item->frequency != BillFrequency::Monthly) continue;
            $total += ($item->price * $item->qty) + $item->addonTotal;
        }
        return bcmul($total, 1);
    }

    /**
     * Does this account have at least one contract?
     * @return bool
     */
    public function getHasContractAttribute(): bool
    {
        foreach ($this->quotes as $quote)
        {
            if ($quote->signature) return true;
        }
        return false;
    }

    /**
     * Get a list of contracted quotes.
     * @return array
     */
    public function getContractedQuotes(): array
    {
        $data = [];
        $data[0] = "-- Select Contract --";
        foreach ($this->quotes as $quote)
        {
            if ($quote->signature)
            {
                $data[$quote->id] = sprintf("Contract #%d - Ends %s", $quote->id,
                    $quote->contract_expires?->format("m/d/y"));
            }
        }
        return $data;
    }

    /**
     * Render a City, State and Zip Line
     * @return string
     */
    public function getCszAttribute(): string
    {
        return sprintf("%s, %s %s", $this->city, $this->state, $this->postcode);
    }

    /**
     * Return only open quotes that are customer viewable.
     * @return HasMany
     */
    public function openQuotes(): HasMany
    {
        return $this->hasMany(Quote::class)->where('presentable', true)->where('archived', 0);
    }

    /**
     * Return a formatted table for email notification of suspensions and reason
     * @return string
     */
    public function getSuspensionsAttribute(): string
    {
        $data = "<table width='100%' cellpadding='3'><tr><td align='center'><b>Service</b></td><td align='center'><b>Suspension Date</b></td><td align='center'><b>Reason</b></td></tr>";
        foreach ($this->items()->whereNotNull('suspend_on')->get() as $item)
        {
            $data .= "<tr><td>[{$item->item->code}] - {$item->item->name}<br/>$item->notes</td><td>{$item->suspend_on->format("m/d/y")}</td><td>{$item->suspend_reason}</td></tr>";
        }
        $data .= "</table>";
        return $data;
    }

    /**
     * Return a formatted table for termination notifications.
     * @return string
     */
    public function getTerminationsAttribute(): string
    {
        $data = "<table width='100%' cellpadding='3'><tr>    <td align='center'><b>Service</b></td>    <td align='center'><b>Termination Date</b></td>    <td align='center'><b>Reason</b></td></tr>";
        foreach ($this->items()->whereNotNull('terminate_on')->get() as $item)
        {
            $data .= "<tr><td>[{$item->item->code}] - {$item->item->name}<br/>{$item->notes}</td><td>{$item->terminate_on->format("m/d/y")}</td><td>{$item->terminate_reason}</td></tr>";
        }
        $data .= "</table>";
        return $data;
    }

    /**
     * This will take a quote and apply services to recurring and
     * will build an invoice for one-time. Also, this will set the
     * product pricing based on what was given in the quote for
     * each of the bill items.
     * @param Quote $quote
     * @param bool  $generateMonthly
     * @return void
     */
    public function applyFromQuote(Quote $quote, bool $generateMonthly = false): void
    {
        if (!$this->net_terms)
        {
            $this->update(['net_terms' => $quote->net_terms]);
        }
        // Check to see if the quote has a lead and if it was sourced via a partner.
        if ($quote->lead && $quote->lead->partner && $generateMonthly) // gm assumes new account.
        {
            $this->update([
                'partner_id' => $quote->lead->partner_id,
            ]);
            // We need to notify the partner that this lead is sold.
            $quote->lead->partner->notifySoldLead($quote->lead);
        }
        foreach ($quote->services as $service)
        {
            $item = $this->items()->create([
                'bill_item_id' => $service->item->id,
                'description'  => $service->description ?: $service->item->description,
                'price'        => $service->price,
                'qty'          => $service->qty,
                'notes'        => $service->notes,
                'account_id'   => $this->id,
                'quote_id'     => $quote->id,
                'frequency'    => $service->frequency,
                'meta'         => $service->meta
            ]);
            foreach ($service->addons as $addon)
            {
                $item->addons()->create([
                    'name'                 => $addon->name,
                    'account_bill_item_id' => $item->id,
                    'price'                => $addon->price,
                    'notes'                => $addon->notes,
                    'qty'                  => $addon->qty,
                    'addon_option_id'      => $addon->addon_option_id,
                    'addon_id'             => $addon->addon_id,
                    'account_id'           => $this->id
                ]);
            }
        }
        // Add any financing products.
        foreach ($quote->products as $product)
        {
            if ($product->frequency && $product->payments)
            {
                // Add custom service item with remaining.
                $this->items()->create([
                    'bill_item_id' => $product->item->id,
                    'description'  => $product->item->description,
                    'price'        => $product->frequency->splitTotal($product->price * $product->qty,
                        $product->payments, $product->finance_charge),
                    'qty'          => 1,
                    'notes'        => $product->notes,
                    'account_id'   => $this->id,
                    'quote_id'     => $quote->id,
                    'remaining'    => $product->payments,
                    'frequency'    => $product->frequency
                ]);
            }
        }

        if (count($quote->products) && $quote->invoiceableProducts)
        {
            // Create invoice
            $invoice = $this->invoices()->create(['due_on' => now()->addDays($quote->net_terms)]);
            foreach ($quote->products as $product)
            {
                if ($product->frequency && $product->payments) continue; // Don't separately invoice these.
                $invoice->items()->create([
                    'bill_item_id' => $product->item_id,
                    'code'         => $product->item->code,
                    'name'         => $product->item->name,
                    'description'  => $product->item->description,
                    'qty'          => $product->qty,
                    'price'        => $product->price,
                    'account_id'   => $this->id,
                ]);
                foreach ($product->addons as $addon)
                {
                    $invoice->items()->create([
                        'bill_item_id' => $addon->addon_option_id ? $addon->option->item->id : 0,
                        'code'         => $addon->addon_option_id ? $addon->option->item->name : "ADDON",
                        'name'         => $addon->name,
                        'description'  => "Addon: $addon->name x $addon->qty",
                        'qty'          => $addon->qty,
                        'price'        => $addon->price,
                        'account_id'   => $this->id
                    ]);
                }
            }
            $invoice->send();
            $invoice->createOrder();
        }

        if ($generateMonthly && $quote->services()->count() > 0)
        {
            $this->generateMonthlyInvoice(true);
        }
    }

    /**
     * Send an accounting related email.
     * @param string $template
     * @param array  $models
     * @param array  $attachments
     * @return void
     */
    public function sendBillingEmail(string $template, array $models, array $attachments): void
    {
        if ($this->billing_email)
        {
            $toEmail = $this->billing_email;
            $toName = sprintf("%s Accounting", $this->name);
        }
        else
        {
            $toEmail = $this->admin->email;
            $toName = $this->admin->name;
        }
        template($template, null, $models, $attachments, $toEmail, $toName);
    }

    /**
     * Return if this customer has PBX services
     * @return bool
     */
    public function hasPBXServices(): bool
    {
        foreach ($this->items as $item)
        {
            if ($item->item->creates_pbx) return true;
        }
        return false;
    }

    /**
     * Get the account balance for this account.
     * @return float
     */
    public function getAccountBalanceAttribute(): float
    {
        $total = 0;
        foreach ($this->invoices()->where('status', '!=', InvoiceStatus::DRAFT)->get() as $invoice)
        {
            if ($invoice->balance)
            {
                $total += $invoice->balance;
            }
        }
        return $total;
    }

    /**
     * Get profit analysis stats for an account.
     * @return object
     */
    public function getAnalysisAttribute(): object
    {
        return AnalysisEngine::byAccount($this);
    }

    /**
     * Get previous balance before a particular invoice.
     * @param float $minus
     * @return float
     */
    public function getPreviousBalance(float $minus): float
    {
        $start = $this->getAccountBalanceAttribute();
        $total = $start - $minus;
        if ($total <= 0) return 0;
        return $start - $minus;
    }


    /**
     * Get past due amount on account.
     * @return float
     */
    public function getPastDueAttribute(): float
    {
        $total = 0;
        foreach ($this->invoices()->where('due_on', "<", now())->get() as $invoice)
        {
            $total += $invoice->balance;
        }
        return $total;
    }

    /**
     * Helper for getting all active invoices for a customer
     * @return Collection
     */
    public function getActiveInvoices(): Collection
    {
        return $this->invoices()->whereIn('status', [InvoiceStatus::SENT, InvoiceStatus::PARTIAL])->get();
    }


    /**
     * Get a list of alerts for an account and severity
     * @return array
     */
    public function getAlertsAttribute(): array
    {
        $alerts = [];

        if (hasIntegration(IntegrationType::Finance) && !$this->finance_customer_id)
        {
            $alerts[] = (object)[
                'type'        => 'danger',
                'title'       => "Accounting Integration Alert",
                'description' => "You have an accounting integration and this account is not found or linked.",
                'action'      => "Sync Account Info",
                'url'         => "/admin/accounts/$this->id?sync=financial"
            ];
        }

        if ($this->declined)
        {
            $alerts[] = (object)[
                'type'        => 'danger',
                'title'       => "Account Card Declined",
                'description' => "The last time a card was authorized or attempted a charge, it was declined.",
                'action'      => "Update Credit Card",
                'url'         => "/admin/accounts/$this->id?active=profile"
            ];
        }


        // Check for PBX and PBX Items
        /*
        if ($this->hasPBXServices() && !$this->pbx_domain)
        {
            $alerts[] = (object)[
                'type'        => 'warning',
                'title'       => "No PBX Allocated",
                'description' => "You currently have services assigned to this customer that relate to a PBX but no PBX found.",
                'action'      => "Create/Assign PBX",
                'url'         => "#/admin/accounts/$this->id/pbx/assign" // use # for a livemodal renderer.
            ];
        }
        */
        // No bill date set.
        if (!$this->next_bill)
        {
            $alerts[] = (object)[
                'type'        => 'info',
                'title'       => "No Bill Date Set",
                'description' => "This account has no bill date and will not be sent any automatic invoices until set."
            ];
        }

        if ($this->payment_method == PaymentMethod::CreditCard && !$this->merchant_payment_token)
        {
            $alerts[] = (object)[
                'type'        => 'info',
                'title'       => "No Credit Card Found",
                'description' => "Credit Card has been selected for monthly billing but no card found on file."
            ];
        }

        if ($this->quotes()->whereNull('activated_on')->count())
        {
            $count = $this->quotes()->whereNull('activated_on')->count();
            $alerts[] = (object)[
                'type'        => 'info',
                'title'       => "$count Active Quote(s)",
                'description' => "There are currently $count active quote(s) that have not been sold or discarded.",
                'action'      => "View Quotes",
                'url'         => "/admin/accounts/$this->id?active=invoices"
            ];
        }

        // PBX Seat Alert
        /*
        if ($this->getActualExtensionCount() != $this->getSoldExtensionCount())
        {
            if (!$this->pbx_domain)
            {
                $alerts[] = (object)[
                    'type'        => 'warning',
                    'title'       => "Seats Sold but no PBX Found",
                    'description' => sprintf("You are currently billing %d seats but no PBX found.",
                        $this->getSoldExtensionCount()),
                    'action'      => "Assign PBX",
                    'url'         => "#/admin/accounts/$this->id/pbx/assign" // use # for a livemodal renderer.
                ];
            }
            else
            {
                $alerts[] = (object)[
                    'type'        => 'warning',
                    'title'       => "Seat Sold vs. Actual Mismatch",
                    'description' => sprintf("You are currently billing %d seats total, but %d found in use.",
                        $this->getSoldExtensionCount(), $this->getActualExtensionCount()),
                    'action'      => "Review PBX",
                    'url'         => "/admin/accounts/$this->id?active=pbx"
                ];
            }
        }
        */

        // Draft Invoices
        if ($this->invoices()->where('status', InvoiceStatus::DRAFT)->count())
        {
            $count = $this->invoices()->where('status', InvoiceStatus::DRAFT)->count();
            $alerts[] = (object)[
                'type'        => 'info',
                'title'       => "$count Invoice Draft(s)",
                'description' => "There are currently $count draft invoices that have not been sent to the customer.",
                'action'      => "View Invoices",
                'url'         => "/admin/accounts/$this->id?active=invoices"
            ];
        }
        return $alerts;
    }

    /**
     * Do we have this service on the account? This is primarily used
     * to show strikeouts in quotes.
     * @param BillItem $item
     * @return bool
     */
    public function hasService(BillItem $item): bool
    {
        return (bool)$this->items()->where('bill_item_id', $item->id)->count();
    }

    /**
     * Return QTY and Pricing information for an existing service
     * for strikeouts.
     * @param BillItem $item
     * @return object
     */
    public function getServiceDetail(BillItem $item): object
    {
        $item = $this->items()->where('bill_item_id', $item->id)->first();
        return (object)['qty' => $item->qty, 'price' => $item->price, 'total' => bcmul($item->qty * $item->price, 1)];
    }

    /**
     * Get account file counter
     * @param FileCategory $category
     * @return mixed
     */
    public function getFileCount(FileCategory $category)
    {
        return LOFile::where('account_id', $this->id)->where('file_category_id', $category->id)->count();
    }

    /**
     * This method will create an invoice based on the service items, and
     * updates the next bill date.
     * @param bool $createOrder
     * @return void
     */
    public function generateMonthlyInvoice(bool $createOrder = false): void
    {
        BillingEngine::generateMonthlyInvoice($this, $createOrder);
    }

    /**
     * Get total MRR across the system
     * @return float
     */
    static public function getTotalMRR(): float
    {
        $total = 0;
        foreach (Account::where('active', true)->get() as $acc)
        {
            $total += $acc->mrr;
        }
        return $total;
    }

    /**
     * Get Items by Category
     * @return array
     */
    public function itemsByCategory(): mixed
    {
        $data = [];
        $items = AccountItem::select('account_items.*')
            ->join('bill_items', 'bill_items.id', '=', 'account_items.bill_item_id')
            ->orderBy('bill_items.bill_category_id')
            ->where('account_items.account_id', $this->id)
            ->with('item')
            ->get();
        $currCategory = 0;
        $i = [];
        foreach ($items as $item)
        {

            if ($currCategory == 0)
            {
                $currCategory = $item->item->bill_category_id;
            }
            if ($item->item->bill_category_id != $currCategory && $currCategory > 0)
            {
                $cat = BillCategory::find($currCategory);
                $data[] = (object)[
                    'slug'        => Str::slug($cat->name),
                    'name'        => $cat->name,
                    'description' => $cat->description,
                    'items'       => $i
                ];
                $i = [];
                $currCategory = $item->item->bill_category_id; // move cat pointer
                // Dump contents into our array.
            }
            $i[] = $item;
        }
        // We exited.. now see if our $i array is still got stuff in it.. if so dump it and get out
        if (!empty($i))
        {
            $cat = BillCategory::find($currCategory);
            $data[] = (object)[
                'slug'        => Str::slug($cat->name),
                'name'        => $cat->name,
                'description' => $cat->description,
                'items'       => $i
            ];
        }
        return $data;
    }

    /**
     * This will send a declined notification to the user if the declined flag has not been set yet.
     * @param Invoice $invoice
     * @param int     $amount
     * @return void
     */
    public function sendDeclinedNotification(Invoice $invoice, int $amount): void
    {
        if ($this->declined) return; // Do not send duplicates.
        $invoice->amt = moneyFormat($amount);
        template('account.declined', $this->admin, [$this, $invoice]);
        $this->update(['declined' => 1]);
    }


    /**
     * Return a streamed PDF.
     * @param bool $save
     * @return mixed
     * @throws NexusException
     */
    public function statement(bool $save = false): mixed
    {
        $pdf = new MakePDF();
        $pdf->setName("Statement-$this->id.pdf");
        $data = view("pdf.statement")->with('account', $this)->render();
        if (!$save)
        {
            return $pdf->streamFromData($data);
        }
        else return storage_path() . "/" . $pdf->saveFromData($data);
    }

    /**
     * Get a list of PBX users. Used to pull stats and graphs
     * @return array
     */
    public function getPBXUsers(): array
    {
        $key = "PBX_USERS_$this->id";
        if (cache($key)) return cache($key);
        if (!$this->pbx_domain) return [];
        try
        {
            $d = new NSUser($this->provider);
            return $d->list($this->pbx_domain);
        } catch (GuzzleException|LogicException $e)
        {
            info("Failed to get PBX Users for $this->name - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a credit card authorization link
     * @return string
     */
    public function getCreditCardLinkAttribute(): string
    {
        if (!$this->cc_reset_hash)
        {
            $this->update(['cc_reset_hash' => uniqid('CC-')]);
            $this->refresh();
        }
        return sprintf("%s/payment/%s", setting('brand.url'), $this->cc_reset_hash);
    }

    /**
     * Manually pull metrics for user stats.
     * @param string $user
     * @return object
     */
    public function getPBXUserStat(string $user): object
    {
        $data = (object)[
            'calls'   => 0,
            'minutes' => 0
        ];
        return $data;
        /*$calls = Metric::where('account_id', $this->id)
            ->where('stamp', now()->subDay()->format("Y-m-d"))
            ->where('detail', $user)
            ->where('metric', MetricType::PBX_ExtCalls->value)
            ->orderBy('created_at', 'DESC')
            ->first();
        if ($calls) $data->calls = $calls->value;
        $min = Metric::where('account_id', $this->id)
            ->where('stamp', now()->subDay()->format("Y-m-d"))
            ->where('detail', $user)
            ->where('metric', MetricType::PBX_ExtMin->value)
            ->orderBy('created_at', 'DESC')
            ->first();
        if ($calls) $data->minutes = $min->value;
        return $data;
        */
    }

    /**
     * Apply a credit memo to the account.
     * @param float  $amount
     * @param string $reason
     * @return void
     */
    public function applyCredit(float $amount, string $reason): void
    {
        $this->update([
            'account_credit'        => $this->account_credit + abs($amount),
            'account_credit_reason' => $reason
        ]);
    }

    /**
     * Attempt to get a favicon from the webaddress
     * @return void
     */
    public function getFavIcon(): void
    {
        $this->refresh();
        dispatch(new ImageDiscoveryJob($this));
    }


    /**
     * Get a list of helpful widgets in order to get people started.
     * @return array
     */
    static public function gettingStarted(): array
    {
        $help = [];
        if (!setting('brand.address'))
        {
            $help[] = (object)[
                'icon'   => '453603.png',
                'title'  => "Brand Address",
                'body'   => "Update your company address for quotes/invoices and shop information",
                'action' => "Set your Address",
                'target' => "/admin/settings"
            ];
        }

        if (!setting('brandImage.light'))
        {
            $help[] = (object)[
                'icon'   => '5448104.png',
                'title'  => "Company Logo",
                'body'   => "A company logo is needed for your quotes/invoices and shop",
                'action' => "Upload your Logo",
                'target' => "/admin/settings"
            ];
        }

        if (!setting('mail.host'))
        {
            $help[] = (object)[
                'icon'   => '726623.png',
                'title'  => "Mailer Setup",
                'body'   => "You have no mailer configured for Logic to use. A mailer is required to send quotes and invoices.",
                'action' => "Configure Mail",
                'target' => "/admin/settings?tab=mail"
            ];
        }

        if (BillCategory::count() == 0)
        {
            $help[] = (object)[
                'icon'   => '6724239.png',
                'title'  => "Service/Products",
                'body'   => "Create your first service category for your catalog",
                'action' => "View Service Categories",
                'target' => "/admin/bill_categories/services"
            ];
        }

        return $help;

    }

    /**
     * Get the next bill date formatted for an email
     * @return string
     */
    public function getBillDateFormattedAttribute(): string
    {
        return $this->next_bill->format("F d, Y");
    }

    /**
     * Get a MRR formatted for email
     * @return string
     */
    public function getMrrFormattedAttribute(): string
    {
        return "$" . number_format($this->mrr, 2);
    }

    /**
     * Get alternate quotes for copy.
     * @param Quote $quote
     * @return array
     */
    public function alternateQuotes(Quote $quote): array
    {
        $data = [];
        foreach ($this->quotes()->where('id', '!=', $quote->id)->get() as $q)
        {
            $data[$q->id] = sprintf("%s (#%d)", $q->name, $q->id);
        }
        return $data;
    }


    /**
     * Get list of services to send to customer
     * @return string
     */
    public function getServiceFormattedAttribute(): string
    {
        $data = "<table border=1 width='100%' cellpadding=3><tr><td align='center'><b>Service</b></td><td align='center'><b>Price</b></td><td align='center'><b>QTY</b></td></tr>";
        foreach ($this->items as $item)
        {
            $data .= "<tr><td>[{$item->item->code}] {$item->item->name}
<span class='font-size:12px;'>{$item->item->description}</span>
<span class='font-size:12px;'>{$item->notes}</span></td><td>$" . number_format($item->price,
                    2) . "</td><td>{$item->qty}</td></tr>";
        }
        $data .= "</table>
";
        return $data;
    }

    /**
     * Send customer a notification that they have an update to their billing.
     * @return void
     */
    public function sendServiceUpdateNotification(): void
    {
        template('account.updatedServices', $this->admin, [$this]);
        $this->update(['services_changed' => false]);
    }


    /**
     * Send Suspension Notice
     * @return void
     */
    public function sendSuspensionNotice(): void
    {
        template('account.suspend', $this->admin, [$this]);
    }


    /**
     * Send Termination Notice
     * @return void
     */
    public function sendTerminationNotice(): void
    {
        template('account.terminate', $this->admin, [$this]);
    }

    /**
     * Generate Hash for account
     * @return void
     */
    public function generateHash(): void
    {
        $this->update(['hash' => uniqid('A-')]);
    }

    /**
     * Get a list of accounts that this account could be parented to.
     * @return array
     */
    public function getSelectableParents(): array
    {
        $data = [];
        $data[''] = '-- Select Parent Account --';
        foreach (self::whereNull('parent_id')->where('id', '!=', $this->id)->orderBy('name')->get() as $account)
        {
            $data[$account->id] = $account->name;
        }
        return $data;
    }

    /**
     * If we are working on a quote for an account, or an invoice
     * or displaying products while a customer is logged in, return
     * the pricing for an item.
     * @param BillItem $item
     * @return int
     */
    public function getPreferredPricing(BillItem $item): int
    {
        // First check is to see if the actual account has special pricing
        $pricing = $this->pricings()->where('bill_item_id', $item->id)->first();
        if ($pricing && $pricing->price) return $pricing->price;
        // If not found, check to see if this account has a parent.
        // If it does then check the child pricing and apply automatically
        if ($this->parent)
        {
            $pricing = $this->parent->pricings()->where('bill_item_id', $item->id)->first();
            if ($pricing && $pricing->price_children) return $pricing->price_children;
        }
        // If neither are found, return the catalog price.
        return $item->type == 'services' ? $item->mrc : $item->nrc;
    }

}
