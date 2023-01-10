<?php

namespace App\Enums\Core;

use App\Models\Commission;

enum CommissionStatus: string
{
    case PendingPayment = "PENDING_PAYMENT";
    case Scheduled = "SCHEDULED";
    case Sent = "SENT";
    case Paid = "PAID";

    /**
     * Get human readable description
     * @return string
     */
    public function getHuman() : string
    {
        return match($this){
          self::PendingPayment => "Pending Invoice Payment",
          self::Scheduled => "Scheduled for Payment",
          self::Sent => "Commission Sent/Mailed",
          self::Paid => "Commission Paid"
        };
    }

    /**
     * Get a count of how many commissions in this current state.
     * @return int
     */
    public function count() : int
    {
        return Commission::where('status', $this)->count();
    }
}
