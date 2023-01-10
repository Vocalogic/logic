<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadDiscovery extends Model
{
    protected $guarded = ['id'];

    /**
     * A discovery entry belongs to a lead.
     * @return BelongsTo
     */
    public function lead():BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

}
