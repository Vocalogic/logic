<?php

namespace App\Operations\Core;

use App\Enums\Core\ActivityType;
use App\Models\Lead;

/**
 * This class will check for any daily notifications that need to be sent out to either admins
 * or sales agents. This should be run during the TaskDaily routine.
 */
class NotificationEngine
{
    /**
     * Main entry point to run all notification checks.
     * @return void
     */
    static public function run() : void
    {
        $x = new self;
        $x->checkStaleLeads();
    }


    /**
     * Check for stale leads, and email the agent assigned.
     * @return void
     */
    public function checkStaleLeads() : void
    {
        foreach(Lead::where('active', true)->get() as $lead)
        {
            if ($lead->requires_update && !$lead->stale_notification_sent) // Lead is stale
            {
                if (!$lead->agent) continue; // No agent to email
                template('sales.staleLead', $lead->agent, [$lead]);
                $lead->timestamps = false; // A notification should not be considered an update.
                $lead->stale_notification_sent = now();
                $lead->save();
                sysact(ActivityType::Lead, $lead->id, "sent a stale lead notification to {$lead->agent->name}");
                _log($lead, "Stale notification sent to agent.");
            }
        }
    }

}
