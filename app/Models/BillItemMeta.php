<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $id
 */
class BillItemMeta extends Model
{
    public $table = 'bill_item_meta';
    protected $guarded = ['id'];

    /**
     * Metadata belongs to an item.
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(BillItem::class);
    }

}
