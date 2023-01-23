<?php

namespace App\Operations\Integrations\Calendar;

use App\Enums\Core\IntegrationRegistry;
use App\Operations\Integrations\BaseIntegration;
use App\Operations\Integrations\Integration;

class Calendly extends BaseIntegration implements Integration
{
    public IntegrationRegistry $ident = IntegrationRegistry::Calendly;


    public function getName(): string
    {
        return "Calendly";
    }

    public function getWebsite(): string
    {
        return "https://www.calendly.com";
    }

    public function getDescription(): string
    {
        return "Calendly is your scheduling automation platform for eliminating the back-and-forth emails for finding the perfect time — and so much more.";
    }

    public function getLogo(): string
    {
        return "/assets/images/integrations/calendly.png";
    }

    /**
     * Get required configuration
     * @return array
     */
    public function getRequired(): array
    {
        return [
            (object)[
                'var'         => 'calendly_pat',
                'item'        => "Calendly Personal Access Token:",
                'description' => "Paste your personal access token from Calendly",
                'default'     => '',
                'protected'   => true,
            ],
            (object) [
                'var'         => 'calendly_uid',
                'item'        => "Calendly User Identifier (auto-filled):",
                'description' => "Stored when a successful connection is made to Calendly",
                'default'     => '',
                'protected'   => false,
            ],
            (object) [
                'var'         => 'calendly_oid',
                'item'        => "Calendly Organization Identifier (auto-filled):",
                'description' => "Stored when a successful connection is made to Calendly",
                'default'     => '',
                'protected'   => false,
            ],

        ];
    }
}
