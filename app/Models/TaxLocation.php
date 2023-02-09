<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $location
 */
class TaxLocation extends Model
{
    protected $guarded = ['id'];

    /**
     * Get a relation of all taxes collected for a location
     * @return HasMany
     */
    public function collected(): HasMany
    {
        return $this->hasMany(TaxCollection::class, 'tax_location_id');
    }
    /**
     * Get rate by location helper. This is used by the quote and invoice
     * routines to check for applicable tax rates based on the state/location.
     * @param string|null $location
     * @return float|null
     */
    static public function findByLocation(?string $location) : ?float
    {
        if (!$location) return 0;
        $location = self::where('location', $location)->first();
        return $location->rate ?: 0;
    }

}
