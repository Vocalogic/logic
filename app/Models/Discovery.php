<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $opts
 */
class Discovery extends Model
{
    protected $guarded = ['id'];

    /**
     * A discovery belongs to a particular lead type.
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(LeadType::class, 'lead_type_id');
    }

    /**
     * Return a selectable array
     * @return array
     */
    public function getSelectableAttribute(): array
    {
        $data = [];
        $x = explode(",", $this->opts);
        foreach ($x as $v)
        {
            $data[$v] = $v;
        }
        return $data;
    }
}
