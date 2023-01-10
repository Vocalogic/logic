<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property mixed $items
 */
class PartnerInvoice extends Model
{
    protected $guarded = ['id'];
    public $dates = ['paid_on'];
    public $appends = ['total'];

    /**
     * An invoice has many items.
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(PartnerInvoiceItem::class, 'partner_invoice_id');
    }

    /**
     * And invoice is FROM a partner.
     * @return BelongsTo
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the total for an invoice.
     * @return float
     */
    public function getTotalAttribute(): float
    {
        $total = 0;
        foreach ($this->items as $item)
        {
            $total += $item->amount;
        }
        return round($total,2);
    }

}
