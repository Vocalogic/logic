<?php

namespace App\Enums\Core;

/**
 * Define Service Types
 */
enum BillItemType : string
{
    case SERVICE = 'services';
    case PRODUCT = 'products';
}
