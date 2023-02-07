<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxCollection extends Model
{
    protected $guarded = ['id'];

    /**
     * A tax collection item belongs to a batch.
     * @return BelongsTo
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(TaxBatch::class, 'tax_batch_id');
    }

    /**
     * A tax is bound to an invoice.
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * A collection belongs to a location.
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(TaxLocation::class, 'tax_location_id');
    }
}
