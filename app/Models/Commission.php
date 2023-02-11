<?php

namespace App\Models;

use App\Enums\Core\CommissionStatus;
use App\Traits\HasLogTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $amount
 * @property mixed $invoice
 * @property mixed $user
 * @property mixed $affiliate
 */
class Commission extends Model
{
    use HasLogTrait;
    protected $guarded = ['id'];

    public       $casts   = ['status' => CommissionStatus::class];
    public       $dates   = ['scheduled_on'];
    public array $tracked = [
        'status'              => "Commission Status|enum",
        'scheduled_on'        => "Commission Scheduled",
        'amount'              => "Commission Amount|money",
        'active'              => "Commission Active State|bool",
        'commission_batch_id' => "Batched Commission",
        'edit_note'           => "Commission Notes",
        'affiliate_id'        => "Affiliate"
    ];

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
     * A commission could belong to an affiliate not a user/agent
     * @return BelongsTo
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
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

    /**
     * Send email template for notification of a new commission.
     * @return void
     */
    public function notifyNew(): void
    {
        if ($this->affiliate)
        {
            template('agent.commission', null, [$this], [], $this->affiliate->email, $this->affiliate->name);
            return;
        }
        template('agent.commission', $this->user, [$this]);
    }

    /**
     * Get amount formatted for email
     * @return string
     */
    public function getAmountHumanAttribute(): string
    {
        return "$" . moneyFormat($this->amount);
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
