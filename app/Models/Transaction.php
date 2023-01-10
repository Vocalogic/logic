<?php

namespace App\Models;

use App\Enums\Core\PaymentMethod;
use App\Operations\Integrations\Merchant\Merchant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $amount
 * @property mixed $account
 * @property mixed $invoice
 * @property mixed $method
 * @property mixed $created_at
 * @property mixed $id
 * @property mixed $finance_transaction_id
 * @property mixed $fee
 * @property mixed $remote_transaction_id
 */
class Transaction extends Model
{
    protected $guarded = ['id'];

    /**
     * A payment belongs to an invoice.
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * A transaction belongs to an account.
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get a formatted amount for a transaction
     * @return string
     */
    public function getAmountFormattedAttribute(): string
    {
        return "$" . moneyFormat($this->amount);
    }

    /**
     * Send transaction email.
     * @return void
     */
    public function send(): void
    {
        $this->account->sendBillingEmail('account.payment', [$this, $this->invoice], [$this->invoice->pdf(true)]);
    }

    /**
     * Attempts to get a transaction fee if a credit card and the integration
     * supports getting a transaction fee per transaction.
     * @return void
     */
    public function updateFee(): void
    {
        if ($this->method != PaymentMethod::CreditCard->value) return; // Right now just CCs
        $m = new Merchant();
        $m->syncFee($this);
    }

    /**
     * Get the Net Deposit amount.
     * @return float
     */
    public function getNetAttribute(): float
    {
        return $this->amount - $this->fee;
    }

    /**
     * Return a name for an activity
     * @return string
     */
    public function getNameAttribute(): string
    {
        return "Transaction #$this->id";
    }

}
