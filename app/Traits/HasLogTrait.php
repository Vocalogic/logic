<?php

namespace App\Traits;

use App\Models\AppLog;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * this is a trait for all the models that have written log entries in AppLog
 */
trait HasLogTrait
{
    /**
     * relation to log entries
     * @return mixed
     */
    public function logs(): MorphMany
    {
        return $this->morphMany(AppLog::class, 'loggable', 'type', 'type_id');
    }

    /**
     * returns link to log entries list
     * @return array
     */
    public function getLogLinkAttribute(): array
    {
        $classpath = explode('\\', self::class);
        $classname = array_pop($classpath);
        return ['type' => $classname, 'id' => $this->id];
    }

    /**
     * Get logs for a model and it's relationships.
     * @return Collection
     */
    public function getLogs(): Collection
    {
        $logs = $this->logs()->orderBy('created_at', 'desc')->get();
        if (isset($this->logRelationships))
        {
            foreach ($this->logRelationships as $relationship)
            {
                if (is_iterable($this->{$relationship})) // for hasMany and others
                {
                    foreach ($this->{$relationship} as $item)
                    {
                        $logs = $logs->concat($item->logs()->get());
                    }
                }
                else // for BelongsTo - not an array.
                {
                    if ($this->{$relationship}) // make sure not empty
                    {
                        $logs = $logs->concat($this->{$relationship}->logs()->get());
                    }
                }
            }
        }
        return $logs->sortByDesc('created_at');
    }
}
