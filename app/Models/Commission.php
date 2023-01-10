<?php

namespace App\Models;

use App\Enums\Core\CommissionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $amount
 * @property mixed $invoice
 * @property mixed $user
 */
class Commission extends Model
{
    protected $guarded = ['id'];

    public $casts = ['status' => CommissionStatus::class];
    public $dates = ['scheduled_on'];

    /**
     * Commission belongs to an account
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Commission sold by a particular user.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Commission off an invoice.
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * A commission belongs to a batch when it it's ready to be paid.
     * @return BelongsTo
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(CommissionBatch::class, 'commission_batch_id');
    }

    public function notifyNew() : void
    {
        template('agent.commission', $this->user, [$this]);

    }

    /**
     * Get amount formatted for email
     * @return string
     */
    public function getAmountHumanAttribute(): string
    {
        return "$" . number_format($this->amount,2);
    }

    /**
     * Get the account name for this commission.
     * @return string
     */
    public function getAccountHumanAttribute(): string
    {
        return $this->invoice->account->name;
    }

}
