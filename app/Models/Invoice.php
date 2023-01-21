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
 */
class Invoice extends Model
{
    protected $guarded = ['id'];

    public $dates = ['sent_on', 'due_on', 'paid_on'];
    public $casts = ['status' => InvoiceStatus::class];

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
        return $total;
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
     * @return int
     */
    public function getBalanceAttribute(): int
    {
        $total = $this->total;
        foreach ($this->transactions as $transaction)
        {
            $total -= $transaction->amount;
        }
        return $total;
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
        return round($total, 2);
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
        $actData = "Balance: $".moneyFormat($this->balance) ." / Due on " . $this->due_on->format("m/d/y");
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
            && ($this->status == InvoiceStatus::SENT->value || $this->status == InvoiceStatus::PARTIAL->value)
            && now()->timestamp > $this->due_on->timestamp)
        {
            return true;
        }
        return false;
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
        if (!$this->account->partner_id && !$this->account->agent_id) return;   // Not sold by a partner or agent
        if (!$this->account->is_commissionable) return;                         // Not Commissionable
        if ($this->commission) return;                                          // already has a commission
        // Get MRR totals.
        $mrrTotal = 0;
        $amount = 0;
        foreach ($this->items as $item)
        {
            if ($item->item && $item->item->type == 'services')
            {
                $mrrTotal += $item->qty * $item->price;
            }
        }
        $total = $this->getTotalAttribute(); // Total of Invoice

        if ($this->account->agent && $this->account->agent->account->id == 1) // Is this agent local?
        {
            if ($this->account->agent->agent_comm_mrc > 0)
            {
                $per = $this->account->agent->agent_comm_mrc / 100; // Take 10 and make it .1
                $amount = round($mrrTotal * $per, 2);
            }
            elseif ($this->account->agent->agent_comm_spiff > 0 && !$this->account->spiffed)
            {
                $amount = $total * $this->account->agent->agent_comm_spiff;
            }
        } // if internal account
        else // This is a partner account which has different commission percentages.
        {
            if (!$this->account->agent->partner_nrc) // Don't include NRC
            {
                $total = $mrrTotal;
            }
            switch ($this->account->partner->partner_commission_type)
            {
                case 'MRR' :
                    $per = $this->account->partner->partner_commission_mrr / 100; // Take 10 and make it .1
                    $amount = round($mrrTotal * $per, 2);
                    break;
                case 'SPIFF':
                    if ($this->account->spiffed) return; // We already spiffed this.
                    $amount = $total * $this->account->partner->partner_commission_spiff;
                    break;
                case 'BOTH' :
                    $per = $this->account->partner->partner_commission_mrr / 100; // Take 10 and make it .1
                    $amount = round($mrrTotal * $per, 2);
                    if (!$this->account->spiffed)
                    {
                        $amount += $total * $this->account->partner->partner_commission_spiff;
                    }
                    break;
            }
        }
        $this->account->update(['spiffed' => true]);
        if ($amount > 0)
        {
            $comm = $this->commission()->create([
                'account_id' => $this->account->partner_id ?: 1, // No partner, set as house account.
                'user_id'    => $this->account->agent_id,
                'status'     => CommissionStatus::PendingPayment,
                'amount'     => $amount
            ]);
            $comm->refresh();
            $comm->notifyNew();

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
}
