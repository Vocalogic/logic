<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $is_lost
 * @property mixed $is_won
 */
class LeadStatus extends Model
{
    protected $guarded = ['id'];

    /**
     * A lead status has many leads.
     * @return HasMany
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'lead_status_id');
    }

    /**
     * Get a selectable array for lead status
     * @param bool $onlyLost
     * @param bool $force
     * @return array
     */
    static public function getSelectable(bool $onlyLost = false, bool $force = false): array
    {
        $data = [];
        if (!$onlyLost || $force)
        {
            $data[''] = "-- Select Status --";
        }
        foreach (self::orderBy('name')->get() as $status)
        {
            if (!$force)
            {
                if ($onlyLost && !$status->is_lost) continue;
                if (!$onlyLost && $status->is_lost) continue;
            }
            $tail = null;
            if ($status->is_won) $tail = ' (won)';
            if ($status->is_lost) $tail = ' (lost)';
            $data[$status->id] = $status->name . $tail;
        }
        return $data;
    }

    /**
     * Helper for easy selection of what type of lead this is.
     * @return string
     */
    public function getSelected(): string
    {
        if ($this->is_won) return 'won';
        if ($this->is_lost) return 'lost';
        return 'progress';
    }

}
