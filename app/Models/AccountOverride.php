<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountOverride extends Model
{
    protected $guarded = ['id'];

    /**
     * Pivot to Account
     * @return BelongsTo
     */
    public function account():BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Pivot to Bill Item
     * @return BelongsTo
     */
    public function item():BelongsTo
    {
        return $this->belongsTo(BillItem::class, 'bill_item_id');
    }

}
