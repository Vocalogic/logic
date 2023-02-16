<?php

namespace App\Models;

use App\Enums\Core\ActivityType;
use App\Enums\Core\BillItemType;
use App\Enums\Core\CommissionStatus;
use App\Enums\Core\IntegrationType;
use App\Enums\Core\InvoiceStatus;
use App\Enums\Core\LNPStatus;
use App\Enums\Core\OrderStatus;
use App\Enums\Core\PaymentMethod;
use App\Operations\Admin\AnalysisEngine;
use App\Operations\Core\MakePDF;
use App\Operations\Integrations\Accounting\Finance;
use App\Operations\Integrations\Merchant\Merchant;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use App\Exceptions\LogicException;
use App\Traits\HasLogTrait;

/**
 * @property mixed        $items
 * @property mixed        $total
 * @property mixed        $transactions
 * @property mixed        $account
 * @property mixed        $id
 * @property mixed        $balance
 * @property mixed        $due_on
 * @property mixed        $account_id
 * @property mixed        $status
 * @property mixed        $commission
 * @property mixed        $finance_invoice_id
 * @property mixed        $total_formatted
 * @property mixed        $sent_on
 * @property mixed|string $amt
 * @property mixed        $last_notice_sent
 * @property mixed        $servicesTotal
 * @property mixed        $recurring
 * @property mixed        $suspension_sent
 * @property mixed        $termination_sent
 * @property mixed        $tax
 * @property mixed        $has_late_fee
 * @property mixed        $lateFee
 */
class Invoice extends Model
{
    use HasLogTrait;

    protected $guarded = ['id'];

    public       $casts            = [
        'status'  => InvoiceStatus::class,
        'sent_on' => 'datetime',
        'due_on'  => 'datetime',
        'paid_on' => 'datetime'
    ];
    public array $tracked          = [
        'status'           => "Invoice Status|enum",
        'sent_on'          => "Sent On",
        'paid_on'          => "Paid On",
        'po'               => "Purchase Order #",
        'suspension_sent'  => "Suspension Notice Sent",
        'termination_sent' => "Termination Notice Sent",
        'tax'              => "Tax Amount|money",
        'has_late_fee'     => "Sent Late Fee Notification|bool",
        'total'            => "Invoice Total|money",
    ];
    public array $logRelationships = ['items', 'transactions', 'commission'];

    /**
     * An invoice belongs to an account
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * An invoice has many items
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * An invoice has many transactions.
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * An invoice can have an order associated.
     * @return HasOne
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    /**
     * An invoice can have a single commission (internal comm)
     * @return HasOne
     */
    public function commission(): HasOne
    {
        return $this->hasOne(Commission::class);
    }

    /**
     * An invoice can have a partner invoice id for remote payout.
     * @return BelongsTo
     */
    public function partnerInvoice(): BelongsTo
    {
        return $this->belongsTo(PartnerInvoice::class, 'partner_invoice_id');
    }

    /**
     * Get subtotal for invoice.
     * @return int
     */
    public function getSubtotalAttribute(): int
    {
        $total = 0;
        foreach ($this->items as $item)
        {
            $total += ($item->price * $item->qty);
        }
        return bcmul($total, 1);
    }

    /**
     * Get total for invoice.
     * @return int
     */
    public function getTotalAttribute(): int
    {
        $total = 0;
        foreach ($this->items as $item)
        {
            $total += ($item->price * $item->qty);
        }
        $total += $this->tax;
        return bcmul($total, 1);
    }

    /**
     * Return a link for customers to be able to log in
     * and directly view their invoice.
     * @return string
     */
    public function getLinkAttribute(): string
    {
        return sprintf("%s/shop/account/invoices/%d", setting('brand.url'), $this->id);
    }


    /**
     * Get balance on an invoice.
     * @return float
     */
    public function getBalanceAttribute(): float
    {
        $total = $this->total;
        foreach ($this->transactions as $transaction)
        {
            $total -= $transaction->amount;
        }
        return bcmul($total, 1);
    }

    /**
     * Get the balance in a formatted dollar string.
     * @return string
     */
    public function getBalanceFormattedAttribute(): string
    {
        return "$" . moneyFormat($this->balance);
    }

    /**
     * Get the tax for a quote.
     * @return void
     */
    public function calculateTax(): void
    {
        $total = 0;
        $rate = TaxLocation::findByLocation($this->account->state);
        if (!$rate) return;
        if (!$this->account->taxable)
        {
            $this->update(['tax' => 0]);
            return;
        }
        foreach ($this->items as $item)
        {
            if (!$item->item || !$item->item->taxable) continue;
            $itemTotal = bcmul($item->price * $item->qty, 1);
            $tax = bcmul($itemTotal * ($rate / 100), 1);
            $total += $tax;
        }
        $this->update(['tax' => $total]);
    }

    /**
     * This helper is used for email templates and calculations
     * for getting the late fee percentage.
     * @return string
     */
    public function getLateFeePercentageAttribute(): string
    {
        return $this->account->late_fee_percentage ?: setting('invoices.lateFeePercentage');
    }

    /**
     * This helper will determine what a late fee should be on this invoice.
     * @return int
     */
    public function getLateFeeAttribute(): int
    {
        $perc = $this->getLateFeePercentageAttribute();
        if ($perc <= 0) return 0; // avoid divide by zero.
        return bcmul($this->total * ($perc / 100), 1);
    }

    /**
     * Actual Late Fee in Template Format
     * @return string
     */
    public function getLateFeeFormattedAttribute(): string
    {
        return moneyFormat($this->lateFee);
    }

    /**
     * Assess a late fee if not found.
     * @return void
     */
    public function assessLateFee(): void
    {
        if ($this->has_late_fee) return;
        $fee = $this->lateFee;
        $this->items()->create([
            'code'         => "LATE-FEE",
            'name'         => "LATE FEE",
            'description'  => setting('invoices.lateFeeVerbiage'),
            'qty'          => 1,
            'price'        => $fee,
            'bill_item_id' => 0
        ]);
        $this->update(['has_late_fee' => true]);
        $this->refresh();
        _log($this, "Customer charged $" . $this->getLateFeeFormattedAttribute() . " in late fees.");
        $this->account->sendBillingEmail('invoice.lateFeeCharged', [$this], [$this->pdf(true)]);
    }

    /**
     * Return a streamed PDF.
     * @param bool $save
     * @param bool $summary
     * @return mixed
     */
    public function pdf(bool $save = false, bool $summary = true): mixed
    {
        $pdf = new MakePDF();
        $pdf->setName("Invoice-$this->id.pdf");
        $view = $summary ? 'pdf.invoice_summary' : 'pdf.invoice';
        $data = view($view)->with('invoice', $this)->render();
        if (!$save)
        {
            return $pdf->streamFromData($data);
        }
        return storage_path() . "/" . $pdf->saveFromData($data);
    }

    /**
     * Generate an order from an invoice.
     * @return Order
     */
    public function createOrder(): Order
    {
        $order = (new Order)->create([
            'name'       => "Order via Invoice #$this->id for {$this->account->name}",
            'account_id' => $this->account_id,
            'active'     => true,
            'invoice_id' => $this->id,
            'hash'       => uniqid('ORD-')
        ]);
        // Copy invoice items to order items.
        foreach ($this->items as $item)
        {
            $service = $item->item && $item->item->type == BillItemType::SERVICE->value;
            $order->items()->create([
                'product'      => !$service,
                'bill_item_id' => $item->bill_item_id,
                'code'         => $item->code,
                'name'         => $item->name,
                'description'  => $item->description,
                'qty'          => $item->qty,
                'price'        => $item->price,
                'shippable'    => $item->item && $item->item->is_shipped
            ]);
        }
        return $order;
    }

    /**
     * Get partner payout based on partner.
     * @param Partner $partner
     * @return float
     */
    public function getPartnerPayout(Partner $partner): float
    {
        return $partner->commission_out_spiff
            ? $this->servicesTotal * $partner->commission_out_spiff
            : $this->servicesTotal * ($partner->commission_out_mrc / 100);
    }

    /**
     * Get the total amount of services (i.e. MRR) that was
     * generated by this invoice.
     * @return float
     */
    public function getServicesTotalAttribute(): float
    {
        $total = 0;
        foreach ($this->items as $item)
        {
            if ($item->item && $item->item->type == 'services')
            {
                $total += $item->price * $item->qty;
            }
        }
        return bcmul($total, 1);
    }

    /**
     * Return a summary of line items for text in email
     * @return string
     */
    public function getSummaryAttribute(): string
    {
        return view('admin.invoices.summary')->with('invoice', $this)->render();
    }

    /**
     * Send Invoice to Customer
     * @return void
     */
    public function send(): void
    {
        $this->refresh();
        if ($this->total < 0) // This is a credit memo, not an invoice
        {
            $this->account->applyCredit($this->total, "Credit Applied from Invoice #$this->id");
            $this->update(['sent_on' => now(), 'status' => InvoiceStatus::PAID, 'paid_on' => now()]);
            $this->account->sendBillingEmail('account.credit', [$this], [$this->pdf(true)]);
            return;
        }
        $this->createCommission();
        if ($this->balance > 0)
        {
            $this->update(['status' => InvoiceStatus::SENT]);
            $terms = $this->account->net_terms ?: (int)setting('invoices.net');
            if (!$this->sent_on) $this->update(['due_on' => now()->addDays($terms)]);
        }
        $this->update(['sent_on' => now()]);
        $this->refresh();
        $this->account->sendBillingEmail('account.invoice', [$this], [$this->pdf(true)]);
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::syncInvoice($this);
        }
        // Put a notice on the dashboard.
        $actData = "Balance: $" . moneyFormat($this->balance) . " / Due on " . $this->due_on->format("m/d/y");
        sysact(ActivityType::InvoiceSend, $this->id,
            "sent <a href='/admin/accounts/{$this->account->id}'>{$this->account->name}</a>", $actData, true);
    }

    /**
     * Get total formatted for emails
     * @return string
     */
    public function getTotalFormattedAttribute(): string
    {
        return "$" . moneyFormat($this->total);
    }

    /**
     * Get due date formatted.
     * @return string
     */
    public function getDueFormattedAttribute(): string
    {
        return $this->due_on->format("F d, Y");
    }

    /**
     * Return the balance attribute in text format.
     * @return string
     */
    public function getBalanceEmailAttribute(): string
    {
        return sprintf("$%s", moneyFormat($this->balance));
    }

    /**
     * Return the balance attribute in text format.
     * @return string
     */
    public function getTotalEmailAttribute(): string
    {
        return sprintf("$%s", moneyFormat($this->total));
    }

    /**
     * Get when an invoice is due for an email template.
     * @return string
     */
    public function getDueEmailAttribute(): string
    {
        return $this->due_on->format("M d, Y");
    }

    /**
     * Get name attribute for invoice
     * @return string
     */
    public function getNameAttribute(): string
    {
        return sprintf("Invoice #%d", $this->id);
    }

    /**
     * Determine if this invoice is past due.
     * @return bool
     */
    public function getIsPastDueAttribute(): bool
    {
        if ($this->balance > 0
            && ($this->status == InvoiceStatus::SENT || $this->status == InvoiceStatus::PARTIAL)
            && now()->timestamp > $this->due_on->timestamp)
        {
            return true;
        }
        return false;
    }

    /**
     * Determine the discount on an entire quote based on the
     * pricing of each item individually if we have the setting enabled.
     * @return float
     */
    public function getDiscountAttribute(): float
    {
        if (setting('quotes.showDiscount') == 'None') return 0;
        $totalCatalog = 0;
        $totalQuoted = 0;
        foreach ($this->items as $item)
        {
            if (!$item->item) continue; // no catalog price on manual items.
            $totalCatalog += $item->getCatalogPrice() * $item->qty;
            $totalQuoted += $item->price * $item->qty;
        }
        return bcmul($totalCatalog - $totalQuoted, 1);
    }

    /**
     * Apply or process a payment on an invoice
     * @param PaymentMethod $method
     * @param int           $amount
     * @param mixed         $details
     * @return Transaction
     * @throws LogicException
     */
    public function processPayment(PaymentMethod $method, int $amount, mixed $details): Transaction
    {
        $this->createCommission();
        $this->refresh();
        $add = $details ? " ($details)" : null;
        $transaction = null;
        switch ($method)
        {
            case PaymentMethod::PaperCheck :
                $transaction = $this->transactions()->create([
                    'account_id'           => $this->account_id,
                    'amount'               => $amount,
                    'local_transaction_id' => uniqid("TX-"),
                    'details'              => PaymentMethod::PaperCheck->getDescription() . $add,
                    'method'               => PaymentMethod::PaperCheck
                ]);
                break;
            case PaymentMethod::CreditCard :
                // Attempt to authorize a transaction with Merchant
                $m = new Merchant();
                try
                {
                    $transid = $m->authorize($this, $amount);
                    $this->account->update(['declined' => 0]);
                } catch (Exception $e)
                {
                    $this->account->sendDeclinedNotification($this, $amount);
                    throw new LogicException($e->getMessage());
                }
                $transaction = $this->transactions()->create([
                    'account_id'            => $this->account_id,
                    'amount'                => $amount,
                    'local_transaction_id'  => uniqid("TX-"),
                    'remote_transaction_id' => $transid,
                    'details'               => "Payment for Invoice #{$this->id}",
                    'method'                => PaymentMethod::CreditCard
                ]);
                break;
            case PaymentMethod::AccountCredit :
                $transaction = $this->applyAccountCredit($amount);
                break;
            case PaymentMethod::EFT :
                $transaction = $this->transactions()->create([
                    'account_id'           => $this->account_id,
                    'amount'               => $amount,
                    'local_transaction_id' => uniqid("TX-"),
                    'details'              => PaymentMethod::EFT->getDescription() . $add,
                    'method'               => PaymentMethod::EFT
                ]);
                break;
            case PaymentMethod::PayPal :
                $transaction = $this->transactions()->create([
                    'account_id'           => $this->account_id,
                    'amount'               => $amount,
                    'local_transaction_id' => uniqid("TX-"),
                    'details'              => PaymentMethod::PayPal->getDescription() . $add,
                    'method'               => PaymentMethod::PayPal
                ]);
                break;
            case PaymentMethod::Cash :
                $transaction = $this->transactions()->create([
                    'account_id'           => $this->account_id,
                    'amount'               => $amount,
                    'electronic'           => true,
                    'local_transaction_id' => uniqid("TX-"),
                    'details'              => PaymentMethod::Cash->getDescription() . $add,
                    'method'               => PaymentMethod::Cash
                ]);
                break;

            case PaymentMethod::ACH :
                // Attempt to authorize an ACH transaction with Merchant
                $m = new Merchant();
                try
                {
                    $transid = $m->ach($this, $amount);
                    $this->account->update(['declined' => 0]);
                } catch (Exception $e)
                {
                    $this->account->sendDeclinedNotification($this, $amount);
                    throw new LogicException($e->getMessage());
                }
                $transaction = $this->transactions()->create([
                    'account_id'            => $this->account_id,
                    'amount'                => $amount,
                    'local_transaction_id'  => uniqid("TX-"),
                    'remote_transaction_id' => $transid,
                    'details'               => "Payment for Invoice #{$this->id}",
                    'method'                => PaymentMethod::ACH
                ]);
                break;


        }
        $transaction->send();
        // Update invoice status if paid.
        $this->refresh();
        if ($this->balance <= 0)
        {
            $this->update(['status' => InvoiceStatus::PAID, 'paid_on' => now()]);
            $this->processTaxes();
            $order = Order::where('invoice_id', $this->id)->first();
            if ($order)
            {
                $order->logs()->create([
                    'status'  => OrderStatus::PendingInvoicePayment,
                    'note'    => "Invoice #$this->id paid in full.",
                    'user_id' => 0
                ]);
            }
            if ($this->commission)
            {
                $this->commission()->update([
                    'status'       => CommissionStatus::Scheduled,
                    'scheduled_on' => $this->account->partner_net_days
                        ? now()->addDays($this->account->partner_net_days)
                        : now()->addMonth()
                ]);
            }
        }
        else $this->update(['status' => InvoiceStatus::PARTIAL]);
        sysact(ActivityType::NewTransaction, $transaction->id,
            "made a payment of $" . moneyFormat($amount) .
            " to <a href='/admin/invoices/$this->id'>Invoice #$this->id</a> for {$this->account->name} via ");
        _log($transaction, "Payment of $" . moneyFormat($amount) . " applied to Invoice #$this->id");
        return $transaction;
    }

    /**
     * Attempt to apply the account credit.
     * @param float $amount
     * @return Transaction
     * @throws LogicException
     */
    private function applyAccountCredit(float $amount): Transaction
    {
        if ($this->account->account_credit < $amount)
        {
            throw new LogicException("Credit Balance ($" . number_format($this->account->account_credit,
                    2) . ") exceeds amount requested ($" . $amount . ")");
        }
        $transaction = $this->transactions()->create([
            'account_id'           => $this->account_id,
            'amount'               => $amount,
            'local_transaction_id' => uniqid("TX-"),
            'details'              => PaymentMethod::AccountCredit->getDescription(),
            'method'               => PaymentMethod::AccountCredit
        ]);
        $this->account->update(['account_credit' => $this->account->account_credit - $amount]);
        return $transaction;
    }

    /**
     * Get all totals of invoices.
     * @return float
     */
    static public function getAllTotals(): float
    {
        $total = 0;
        foreach (Invoice::where('status', '!=', InvoiceStatus::DRAFT)->get() as $invoice)
        {
            $total += $invoice->total;
        }
        return $total;
    }

    /**
     * Create a commission if sold by a partner.
     * @return void
     */
    private function createCommission(): void
    {
        // Check to see if we have any form of commissionable agent here.
        if (!$this->account->partner_id && !$this->account->agent_id && !$this->account->affiliate_id) return;
        if (!$this->account->is_commissionable) return;                         // Not Commissionable
        if (!$this->recurring) return;                                          // Not a recurring invoice.
        if ($this->commission) return;                                          // already has a commission
        $amount = AnalysisEngine::byInvoice($this);

        $this->account->update(['spiffed' => true]);                            // Do not spiff more than once.
        if ($amount > 0)
        {
            $comm = $this->commission()->create([
                'account_id'   => $this->account->partner_id ?: 1, // No partner, set as house account.
                'user_id'      => $this->account->agent_id ?: 0,
                'affiliate_id' => $this->account->affiliate_id ?: 0,
                'status'       => CommissionStatus::PendingPayment,
                'amount'       => $amount
            ]);
            $comm->refresh();
            $comm->notifyNew();
            _log($comm, "Commission Generated for $" . moneyFormat($amount));
        }
    }

    /**
     * Get invoice count by type.
     * @param InvoiceStatus $status
     * @return int
     */
    static public function getCountByType(InvoiceStatus $status): int
    {
        return self::where('status', $status)->count();
    }

    /**
     * Get number of past due invoices.
     * @return int
     */
    static public function getCountPastDue(): int
    {
        return self::whereIn('status', [InvoiceStatus::SENT, InvoiceStatus::PARTIAL])->where('due_on', '<', now())
            ->count();
    }

    /**
     * Get number of days past due
     * @return int
     */
    public function getDaysPastDueAttribute(): int
    {
        return abs(now()->diffInDays($this->due_on));
    }

    /**
     * Determine if we should send a new past due notification.
     * @return void
     */
    public function sendPastDueNotification(): void
    {
        if (!$this->last_notice_sent)
        {
            $this->account->sendBillingEmail('invoice.pastdue', [$this], [$this->pdf(true)]);
            $this->update(['last_notice_sent' => now()]);
            sysact(ActivityType::PastDueNotification, $this->id,
                "sent a past due notification to {$this->account->name} for ");
            return;
        }
        $target = (int)setting('invoices.pastdue'); // Send every X days
        $daysFromLastSent = abs(now()->diffInDays($this->last_notice_sent));
        if ($daysFromLastSent >= $target)
        {
            $this->account->sendBillingEmail('invoice.pastdue', [$this], [$this->pdf(true)]);
            $this->update(['last_notice_sent' => now()]);
            sysact(ActivityType::PastDueNotification, $this->id,
                "sent a past due notification to {$this->account->name} for ");
        }
    }

    /**
     * Send Termination Notice
     * @return void
     */
    public function sendTerminationNotice(): void
    {
        if ($this->termination_sent) return;
        $this->account->sendBillingEmail('invoice.terminationPending', [$this], [$this->pdf(true)]);
        $this->update(['termination_sent' => now()]);
        sysact(ActivityType::PastDueNotification, $this->id,
            "sent a termination past due notification to {$this->account->name} for ");
    }

    /**
     * Send Suspension Notice
     * @return void
     */
    public function sendSuspensionNotice(): void
    {
        if ($this->suspension_sent) return;
        $this->account->sendBillingEmail('invoice.suspensionPending', [$this], [$this->pdf(true)]);
        $this->update(['suspension_sent' => now()]);
        sysact(ActivityType::PastDueNotification, $this->id,
            "sent a suspension past due notification to {$this->account->name} for ");
    }

    /**
     * Is an invoice not sync'd properly to our finance/accounting integration?
     * @return bool
     */
    public function hasIntegrationError(): bool
    {
        if (!hasIntegration(IntegrationType::Finance)) return false; // No integration
        if ($this->status == InvoiceStatus::DRAFT) return false;     // Don't care about drafts.
        if (!$this->finance_invoice_id) return true;
        return false;
    }

    /**
     * Get all invoices for an account and organize the keys by year
     * @param Account $account
     * @return array
     */
    static public function invoicesByYear(Account $account): array
    {
        $years = [];
        foreach ($account->invoices()->where('status', '!=', 'Draft')->orderBy('created_at', 'DESC')->get() as $invoice)
        {
            $year = $invoice->created_at->format("Y");
            if (!isset($years[$year]))
            {
                $years[$year] = [];
            }
            $years[$year][] = $invoice;
        }
        return $years;
    }

    /**
     * If Logic is handling taxes, then we need to create a tax
     * collection entry. Just take the tax on the invoice and
     * the location.
     * @return void
     */
    private function processTaxes(): void
    {
        // First make sure we don't duplicate tax entries.
        if (TaxCollection::where('invoice_id', $this->id)->count()) return;
        $location = TaxLocation::where('location', $this->account->state)->first();
        if (!$location) return;
        if (getTaxIntegration() !== null) return; // Don't do anything if something else is handling.
        TaxCollection::create([
            'invoice_id'      => $this->id,
            'tax_location_id' => $location->id,
            'amount'          => $this->tax
        ]);
    }
}
