<?php

namespace App\Enums\Core;

/**
 * Categorization of all integration types provided by Logic
 */
enum IntegrationType: string
{
    case Finance = 'FINANCE';
    case Chat = 'CHAT';
    case Merchant = 'MERCHANT';
    case Support = "SUPPORT";
    case Calendar = "CALENDAR";
}
