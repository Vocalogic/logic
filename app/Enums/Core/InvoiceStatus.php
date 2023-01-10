<?php

namespace App\Enums\Core;

/**
 * List the types of statuses that an invoice can be in.
 */
enum InvoiceStatus: string
{
    case DRAFT = 'Draft';
    case SENT = 'Sent';
    case PARTIAL = 'Partial';
    case PAID = 'Paid';
}
