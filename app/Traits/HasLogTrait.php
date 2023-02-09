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
        return AppLog::query()
            ->where('type', self::class)
            ->where('type_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
