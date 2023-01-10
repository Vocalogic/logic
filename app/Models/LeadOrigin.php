<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadOrigin extends Model
{
    protected $guarded = ['id'];

    /**
     * A origin has many leads.
     * @return HasMany
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'lead_origin_id');
    }

}
