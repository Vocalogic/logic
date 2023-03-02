<?php

namespace App\Enums\Core;

enum ProjectStatus: string
{
    case Draft = 'Draft';                       // Building
    case PendingApproval = 'Pending Approval';  // Sent to customer
    case Approved = 'Approved';                 // Pending Start
    case InProgress = 'In Progress';            // Working
    case OnHold = 'On Hold';                    // On hold for info/etc
    case Complete = 'Complete';                 // Completed
}
