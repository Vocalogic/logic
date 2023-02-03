<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $mrr
 * @property mixed $spiff
 */
class Affiliate extends Model
{
    protected $guarded = ['id'];

    /**
     * An affiliate can have many coupon codes
     * @return HasMany
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'affiliate_id');
    }

    /**
     * Return a string representation of how commission is configured
     * @return string
     */
    public function getCommissionAttribute(): string
    {
        if ($this->mrr) return sprintf("%d%% MRR", $this->mrr);
        if ($this->spiff) return sprintf("%d x MRR", $this->spiff);
        return "None";
    }

    /**
     * Create formatted entries for a select box
     * @return array
     */
    static public function getSelectable(): array
    {
        $data = [];
        $data[''] = '-- Select Affiliate --';
        foreach (self::orderBy('name')->get() as $affiliate)
        {
            $data[$affiliate->id] = sprintf("%s (%s)", $affiliate->name, $affiliate->commission);
        }
        return $data;
    }

}
