<?php

namespace App\Models;

use App\Traits\HasLogTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $item
 */
class AccountPricing extends Model
{
    use HasLogTrait;

    public array $tracked = [
        'price'          => "Price|money",
        'price_children' => "Sub-Account Price|money"
    ];
    protected    $guarded = ['id'];

    /**
     * A pricing entry belongs to an account
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * A pricing entry belongs to a BillItem
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(BillItem::class, 'bill_item_id');
    }
}
