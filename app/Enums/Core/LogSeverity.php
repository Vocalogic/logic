<?php

namespace App\Enums\Core;

enum LogSeverity: int
{
    case Info = 0;
    case Error = 1;
    case Debug = 2;

    public function getHuman(): string
    {
        return match($this)
        {
            self::Info => 'Events, information level entries',
            self::Error => "Errors",
            self::Debug => "Technical level log entries",
        };
    }
}