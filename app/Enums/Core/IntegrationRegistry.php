<?php

namespace App\Enums\Core;

use App\Models\Integration;
use App\Operations\Integrations\Accounting\Quickbooks;
use App\Operations\Integrations\Calendar\Calendly;
use App\Operations\Integrations\Chat\Slack;
use App\Operations\Integrations\Merchant\LogicPay;
use App\Operations\Integrations\Merchant\Stripe;
use App\Operations\Integrations\Support\Zendesk;

enum IntegrationRegistry: string
{
    /**
     * Accounting
     */
    case QuickbooksOnline = "qbo";

    /**
     * Merchants
     */
    case Stripe = "stripe";
    case LogicPay = 'logic';

    /**
     * Support Systems
     */
    case Zendesk = "zendesk";

    /**
     * Chat Integrations
     */
    case Slack = "slack";

    /**
     * Calendar Applications
     */
    case Calendly = 'calendly';


    /**
     * Return the class that is used to instantiate.
     * @return string
     */
    public function getIntegration(): string
    {
        return match ($this)
        {
            self::QuickbooksOnline => Quickbooks::class,
            self::Slack => Slack::class,
            self::Stripe => Stripe::class,
            self::Zendesk => Zendesk::class,
            self::LogicPay => LogicPay::class,
            self::Calendly => Calendly::class
        };
    }

    /**
     * Can this integration process credit cards?
     * @return bool
     */
    public function processesCredit(): bool
    {
        return match ($this)
        {
            self::Stripe, self::LogicPay => true,
            default => false
        };
    }

    /**
     * Can this integration process ACH?
     * @return bool
     */
    public function processesACH(): bool
    {
        return match ($this)
        {
            self::LogicPay => true,
            default => false
        };
    }

    /**
     * Instantiate for Chained Methods
     * @return mixed
     */
    public function connect(): mixed
    {
        $class = $this->getIntegration();
        return new $class();
    }

    /**
     * This determines if we should show the authorize button an an integration.
     * @return bool
     */
    public function hasOAuth(): bool
    {
        return match ($this)
        {
            self::QuickbooksOnline => true,
            default => false
        };
    }


    /**
     * Get Integrations by Group
     * @return IntegrationType
     */
    public function getCategory(): IntegrationType
    {
        return match ($this)
        {
            self::QuickbooksOnline => IntegrationType::Finance,
            self::Slack => IntegrationType::Chat,
            self::Stripe, self::LogicPay => IntegrationType::Merchant,
            self::Zendesk => IntegrationType::Support,
            self::Calendly => IntegrationType::Calendar
        };
    }

    /**
     * Get enabled integrations by category.
     * @param IntegrationType $cat
     * @return array
     */
    static public function enabledByCategory(IntegrationType $cat): array
    {
        $enabled = [];
        foreach (self::cases() as $case)
        {
            if ($case->getCategory() == $cat)
            {
                $i = Integration::where('ident', $case->value)->first();
                if (!$i) continue;
                if ($i->enabled) $enabled[] = $case;
            }
        }
        return $enabled;
    }

    /**
     * Get Authorization URL for Service that uses OAuth
     * @return string
     */
    public function getAuthorizationUrl(): string
    {
        return sprintf("/oa/%s/authorize", $this->value);
    }
}
