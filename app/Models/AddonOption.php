<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int   $price
 * @property mixed $item
 * @property mixed $addon
 * @property mixed $name
 * @property mixed $id
 */
class AddonOption extends Model
{
    protected $guarded = ['id'];

    /**
     * An option belongs to an addon.
     * @return BelongsTo
     */
    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }

    /**
     * If bound to a different product or service, we can link it here.
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(BillItem::class, 'bill_item_id');
    }
}
