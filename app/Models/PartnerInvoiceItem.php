<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerInvoiceItem extends Model
{
    protected $guarded = ['id'];

    /**
     * An item belongs to a partner invoice.
     * @return BelongsTo
     */
    public function partnerInvoice(): BelongsTo
    {
        return $this->belongsTo(PartnerInvoice::class);
    }

}
