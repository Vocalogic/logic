<?php

namespace App\Models;

use App\Traits\HasLogTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadDiscovery extends Model
{
    use HasLogTrait;

    protected    $guarded = ['id'];
    public array $tracked = [
        'discovery_id' => "Discovery Question|discovery.question",
        'value'        => "Answer"
    ];

    /**
     * A discovery entry belongs to a lead.
     * @return BelongsTo
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * A lead discovery entry is bound to a discovery question.
     * @return BelongsTo
     */
    public function discovery(): BelongsTo
    {
        return $this->belongsTo(Discovery::class);
    }

}
