<?php

namespace App\Enums\Core;

use App\Models\Account;
use App\Models\AccountItem;
use App\Models\Activity;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Transaction;
use ErrorException;

enum ActivityType: string
{

    case Lead = "LEAD";
    case Account = "ACCOUNT";
    case Order = "ORDER";
    case LeadQuote = "LEAD_QUOTE";
    case AccountQuote = "ACCOUNT_QUOTE";
    case Invoice = 'INVOICE';
    case InvoiceSend = 'INVOICE_SEND';
    case NewTransaction = 'NEW_TRANSACTION';
    case PastDueNotification = 'PAST_DUE';
    case RequestedTermination = 'REQUEST_TERM';

    /**
     * Get verbiage based on type.
     * @param ActivityType $type
     * @return string
     */
    public function getTypeSyntax(self $type): string
    {
        return match ($type)
        {
            self::Lead => "lead",
            self::Account => "account",
            self::Order => "order",
            self::LeadQuote => "quote for a lead",
            self::AccountQuote => "quote for an account",
            self::Invoice => "made a payment on invoice",
            self::InvoiceSend => "sent invoice",
            self::NewTransaction => "paid",
            self::PastDueNotification => "sent a past due notification",
            self::RequestedTermination => "requested service termination"
        };
    }

    /**
     * Get the model referenced by this activitiy
     * @param ActivityType $type
     * @param int          $id
     * @return mixed
     */
    public function getModelReferenced(self $type, int $id): mixed
    {
        return match ($type)
        {
            self::Lead => Lead::find($id),
            self::Account => Account::find($id),
            self::Order => Order::find($id),
            self::LeadQuote, self::AccountQuote => Quote::find($id),
            self::Invoice, self::InvoiceSend, self::PastDueNotification => Invoice::find($id),
            self::NewTransaction => Transaction::find($id),
            self::RequestedTermination => AccountItem::find($id)
        };
    }


    /**
     * Get URL verbiage based on type.
     * @param ActivityType $type
     * @param int          $id
     * @return string
     */
    public function getLinkSyntax(self $type, int $id): string
    {
        try
        {
            $link = match ($type)
            {
                self::Lead => sprintf("/admin/leads/%d", $id),
                self::Account => sprintf("/admin/accounts/%d", $id),
                self::Order => sprintf("/admin/orders/%d", $id),
                self::LeadQuote, self::AccountQuote => sprintf("/admin/quotes/%d", $id),
                self::Invoice, self::PastDueNotification, self::InvoiceSend => sprintf("/admin/invoices/%d", $id),
                self::NewTransaction => sprintf("/admin/transactions/%d", $id),
                self::RequestedTermination => sprintf("/admin/account_item/%d", $id)
            };
            $name = $this->getModelReferenced($type, $id)->name;
        } catch (ErrorException)
        {
            return ""; // Don't crash on deleted records.
        }
        return "<strong><a href='$link'>$name</a></strong>";
    }


    /**
     * Generate the summary line -- called from our models.
     * This should generate a full "Chris created a new lead. Chris added an update, etc.
     * @param Activity $activity
     * @return string
     */
    public function getSummary(Activity $activity): string
    {
        if ($activity->system) // This is a system generated message not a post.
        {
            // Chris Horne created a new lead -- or
            // Lead #1 has gone stale. An update needs to be provided.
            if ($activity->user_id) // Tied to a user.. Chris horne did this..
            {
                // a user id activity should be %s updated Lead %s (first %s should be user, %s is link)
                return sprintf("%s %s %s",
                    $activity->user->name,
                    $activity->activity,
                    $this->getLinkSyntax($activity->type, $activity->refid));
            }
            else
            {
                // %link% %activity
                return sprintf("%s %s %s",
                    "Logic",
                    $activity->activity,
                    $this->getLinkSyntax($activity->type, $activity->refid));
            }
        } // is system.

        // This is not a system message so we should render the activity with a post or event, etc
        // User commented on Lead (name)
        // User added an event to Lead
        // User uploaded a photo to Lead
        if ($activity->user)
        {
            $summary = $activity->user->name;
        }
        elseif ($activity->partner)
        {
            $summary = $activity->partner->name;
        }
        else $summary = "System";
        if ($activity->event)
        {
            $summary .= sprintf(" created an event for %s %s on %s.",
                $this->getTypeSyntax($activity->type),
                $this->getLinkSyntax($activity->type, $activity->refid),
                $activity->event->format("m/d/y h:ia")
            );
        }
        elseif ($activity->image_id)
        {
            $summary .= sprintf(" uploaded a photo to %s %s.",
                $this->getTypeSyntax($activity->type),
                $this->getLinkSyntax($activity->type, $activity->refid));
        }
        else
        {
            $summary .= sprintf(" commented on %s %s",
                $this->getTypeSyntax($activity->type),
                $this->getLinkSyntax($activity->type, $activity->refid));
        }
        return $summary;
    }

    /**
     * Activities are categorized by type. We can associate
     * different channels that map to specific chat integrations.
     * @return ChatChannel|null
     */
    public function getChannel(): ?ChatChannel
    {
        return match ($this)
        {
            self::Lead, self::Account => ChatChannel::Sales,
            self::Order, self::RequestedTermination => ChatChannel::Support,
            self::Invoice, self::PastDueNotification, self::NewTransaction, self::InvoiceSend => ChatChannel::Accounting,
            default => null
        };
    }


}
