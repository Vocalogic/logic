<?php

namespace App\Enums\Core;

/**
 * Define the types of statuses an lead can have.
 */
enum LeadStatus: string
{
    case New = 'New';                                       // Set once accepted
    case QuoteSent = 'Quote Sent';                          // Set when Lead has been sent a quote
    case Lost = 'Lost';                                     // Set when Lead has been lost or cancelled
    case Sold = 'Sold';                                     // Set when a lead is sold
    case Submitted = 'Submitted';                           // Set when a lead is submitted
    case Suspended = 'Suspended';                           // When a lead isn't lost but waiting long term


    /**
     * Gets all status that identifies a lead as open
     * @return LeadStatus[]
     */
    static public function getOpen(): array
    {
        return [
            self::New,
            self::QuoteSent,
        ];
    }


    /**
     * Gets all status that identifies a lead as closed
     * @return LeadStatus[]
     */
    static public function getClosed(): array
    {
        return [
            self::Lost,
            self::Sold,
            self::Suspended
        ];
    }
}
