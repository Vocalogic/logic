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

    /**
     * Get badge colors depending on the status.
     * @return string
     */
    public function getColor() : string
    {
        return match($this)
        {
          self::DRAFT => 'warning',
          self::SENT => 'primary',
          self::PARTIAL => 'info',
          self::PAID => 'success'
        };
    }
}
