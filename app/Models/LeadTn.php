<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadTn extends Model
{
    protected $guarded = ['id'];

    /**
     * A TN belongs to a lead
     * @return BelongsTo
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTO(Lead::class);
    }

}
