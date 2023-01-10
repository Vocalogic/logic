<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteItemAddon extends Model
{
    protected $guarded = ['id'];

    /**
     * A quote item addon can be bound to an option.
     * @return BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(AddonOption::class, 'addon_option_id');
    }

    /**
     * An item addon belongs to a quote item.
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(QuoteItem::class, 'quote_item_id');
    }


}
