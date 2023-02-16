<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $start
 * @property mixed $end
 * @property mixed $remaining
 * @property mixed $dollar_spend_required
 * @property mixed $total_invoice
 * @property mixed $dollars_off
 * @property mixed $percent_off
 * @property mixed $id
 * @property mixed $new_accounts_only
 * @property mixed $affiliate
 */
class Coupon extends Model
{
    protected $guarded = ['id'];
    public $casts = ['start' => 'datetime', 'end' => 'datetime'];

    /**
     * A coupon can have many bill items assigned to it
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(BillItemCoupon::class, 'coupon_id');
    }

    /**
     * A coupon can be associated to an affiliate for commission tracking.
     * @return BelongsTo
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Can this coupon be applied at this time.
     * @return bool
     */
    public function canBeApplied() : bool
    {
        if (!$this->start) return false;
        if (now() < $this->start) return false;
        if ($this->end && now() > $this->end) return false;
        if ($this->remaining < 0) return true; // Unlimited
        if ($this->remaining < 1) return false; // None left.
        return true;
    }

    /**
     * Take a value and determine based on the amount.
     * This is used for discounting a total invoice amount.
     * @param float|int $total
     * @return float
     */
    public function getDiscountAmount(float|int $total) : float
    {
        if ($this->dollars_off)
            return $this->dollars_off;
        else
        {
            // percentage.
            $perc = $this->percent_off / 100;
            return round($total * $perc,2);
        }
    }


}
