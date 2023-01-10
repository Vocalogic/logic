<?php

namespace App\Enums\Core;

enum ShipmentStatus: string
{
    case Draft = 'Draft';
    case Submitted = 'Submitted';
    case Shipped = 'Shipped';
    case Arrived = 'Arrived';
    case Cancelled = 'Cancelled';
}
