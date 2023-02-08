<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxBatch extends Model
{
    protected $guarded = ['id'];

    /**
     * A batch has many tax collection entries.
     * @return HasMany
     */
    public function collections(): HasMany
    {
        return $this->hasMany(TaxCollection::class, 'tax_batch_id');
    }

}
