<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $commissions
 * @property mixed $paidBy
 * @property mixed $agent
 * @property mixed $transaction_detail
 * @property mixed $paid_on
 */
class CommissionBatch extends Model
{
    protected $guarded = ['id'];
    public $dates = ['paid_on'];

    /**
     * A batch has many commissions.
     * @return HasMany
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class, 'commission_batch_id');
    }

    /**
     * Which agent is this batch assigned to
     * @return BelongsTo
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Show paid this commission
     * @return BelongsTo
     */
    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /**
     * Get the total for this batch based on the commissions assigned.
     * @return int
     */
    public function getTotalAttribute(): int
    {
        $total = 0;
        foreach ($this->commissions as $commission)
        {
            $total += $commission->amount;
        }
        return $total;
    }

    /**
     * Email template parseable for paid by.
     * @return string
     */
    public function getPaidByHumanAttribute(): string
    {
        if ($this->paidBy)
        {
            return $this->paidBy->short;
        }
        return "Unknown";
    }

    /**
     * Get total for batch for email template.
     * @return string
     */
    public function getTotalHumanAttribute(): string
    {
        return moneyFormat($this->getTotalAttribute());
    }

    /**
     * Email template by when a commission was paid.
     * @return string
     */
    public function getPaidOnHumanAttribute(): string
    {
        if (!$this->paid_on) return "Unpaid";
        return $this->paid_on->format("m/d/y");
    }

    /**
     * Get transaction detail for email.
     * @return string
     */
    public function getTransactionHumanAttribute(): string
    {
        return $this->transaction_detail;
    }

    /**
     * Notify Agent of a new batch being generated.
     * @return void
     */
    public function notifyNew(): void
    {
        template('agent.batch', $this->agent, [$this]);
    }

    /**
     * Notify Agent that a commission batch has been paid.
     * @return void
     */
    public function notifyPaid(): void
    {
        template('agent.batchPaid', $this->agent, [$this]);
    }

    /**
     * Get a selectable list of batches.
     * @return array
     */
    static public function selectable() : array
    {
        $data = [];
        $data[''] = '-- Select Commission Batch --';
        foreach (self::whereNull('paid_on')->get() as $batch)
        {
            $data[$batch->id] = "Batch #{$batch->id}";
        }
        return $data;
    }

    /**
     * Get a list of commissions for email template.
     * @return string
     */
    public function getListHumanAttribute(): string
    {
        $data = "<table width='100%' cellpadding='3'>";
        $data .= "<tr><td>Invoice</td><td>Account</td><td>Status</td><td>Amount</td></tr>";

        foreach ($this->commissions as $comm)
        {
            $data .= "<tr><td>{$comm->invoice->id}</td><td>{$comm->invoice->account->name}</td><td>{$comm->status->getHuman()}</td><td>$".moneyFormat($comm->amount)."</td></tr>";
        }
        $data .= "</table>";
        return $data;
    }
}
