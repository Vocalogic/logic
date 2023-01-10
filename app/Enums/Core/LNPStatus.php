<?php

namespace App\Enums\Core;

enum LNPStatus: string
{
    case PendingLOA = "Pending LOA";
    case PendingSignature = "Pending Signature";
    case CustomerSigned = "Customer Signed";
    case Submitted = "Submitted";
    case FOC = "FOC";
    case Rejected = "Rejected";
    case Cancelled = "Cancelled";
    case Completed = "Completed";

    /**
     * Get color for list
     * @return string
     */
    public function getColor() : string
    {
        return match($this)
        {
            self::PendingLOA, self::PendingSignature, self::Cancelled => '',
            self::CustomerSigned => 'warning',
            self::Submitted, self::Completed, self::FOC => 'success',
            self::Rejected => 'danger'
        };
    }

}
