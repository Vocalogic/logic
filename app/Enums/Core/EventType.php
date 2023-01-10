<?php

namespace App\Enums\Core;

enum EventType: string
{
    case Storage = 'STORAGE';
    case Mail = 'MAIL';
    case Lead = 'LEAD';
    case Account = 'ACCOUNT';

    case SEV_INFO = "INFO";
    case SEV_WARNING = "WARNING";
    case SEV_ERROR = 'ERROR';
    case SEV_NOTIFY = "NOTIFY";


    /**
     * Get icon for notifications
     * @return string
     */
    public function getIcon(): string
    {
        return match ($this)
        {
            self::Lead => "fa-calendar-o",
            default    => 'fa-circle'
        };
    }
}
