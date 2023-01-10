<?php

namespace App\Operations\Integrations\Calendar;

use App\Enums\Core\IntegrationType;

/**
 * This class will sync down from calendly on events that are not in our local calendar
 * and will also sync up events created for site visits.
 */
class Calendar
{
    public IntegrationType $type = IntegrationType::Calendar;


}
