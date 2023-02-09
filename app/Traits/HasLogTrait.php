<?php

namespace App\Traits;

use App\Models\AppLog;

/**
 * this is a trait for all the models that have written log entries in AppLog
 */
trait HasLogTrait
{
    /**
     * relation to log entries
     * @return mixed
     */
    public function logs()
    {
        return $this->morphMany(AppLog::class, 'loggable', 'type', 'type_id');
    }

    /**
     * returns link to log entries list
     * @return string
     */
    public function getLogLinkAttribute(): string
    {
        $classpath = explode('\\', self::class);
        $classname = array_pop($classpath);
        return "/admin/logs/{$classname}/{$this->id}";
    }
}