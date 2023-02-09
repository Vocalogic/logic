<?php

namespace App\Enums\Core;

enum LogSeverity: int
{
    case Info = 0;
    case Error = 1;
    case Debug = 2;

    /**
     * Return a human readable description of what this
     * log severity is.
     * @return string
     */
    public function getHuman(): string
    {
        return match($this)
        {
            self::Info => 'Events, information level entries',
            self::Error => "Errors",
            self::Debug => "Technical level log entries",
        };
    }

    /**
     * Short name for tabs and such.
     * @return string
     */
    public function getShort(): string
    {
        return match($this)
        {
            self::Info => 'Info',
            self::Error => "Errors",
            self::Debug => "Technical",
        };
    }
}
