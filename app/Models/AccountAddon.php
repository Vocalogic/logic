<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $price
 */
class AccountAddon extends Model
{
    protected $guarded = ['id'];

    /**
     * An account addon belongs to an option.
     * @return BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(AddonOption::class, 'addon_option_id');
    }

    /**
     * Addon belongs to an account
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * An account addon belongs to a base addon.
     * @return BelongsTo
     */
    public function addon():BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }

    /**
     * An addon belongs to an account service item.
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(AccountItem::class, 'account_bill_item_id');
    }
}
