<?php

namespace App\Enums\Core;

/**
 * This enum will define all channels that can be sent to our various
 * chat integrations. These should map to either hooks for slack or
 * rooms for discord, etc.
 */
enum ChatChannel : string
{
    case Sales = "sales";
    case Accounting = "accounting";
    case Support = "support";
}
