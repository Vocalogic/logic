<?php

namespace App\Operations\Integrations\Chat;

use App\Enums\Core\IntegrationRegistry;
use App\Operations\API\Slack\Slack as SlackAPI;
use App\Operations\API\Slack\Slack as SlackIntegration;
use App\Operations\Integrations\BaseIntegration;
use App\Operations\Integrations\Integration;
use GuzzleHttp\Exception\GuzzleException;

class Slack extends BaseIntegration implements Integration, ChatInterface
{
    public IntegrationRegistry $ident = IntegrationRegistry::Slack;
    public SlackIntegration    $iSlack;

    /**
     * Bind Slack
     */
    public function __construct()
    {
        parent::__construct();
        $this->iSlack = new SlackIntegration();
    }

    /**
     * Get application name
     * @return string
     */
    public function getName(): string
    {
        return "Slack";
    }

    /**
     * Get the website for the integration
     * @return string
     */
    public function getWebsite(): string
    {
        return "https://www.slack.com";
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription(): string
    {
        return "Stay on the same page and make decisions faster by bringing all of your work communication into one place.";
    }

    /**
     * Get Logo
     * @return string
     */
    public function getLogo(): string
    {
        return "https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/Slack_Technologies_Logo.svg/2560px-Slack_Technologies_Logo.svg.png";
    }

    /**
     * Get required configuration for slack.
     * @return object[]
     */
    public function getRequired(): array
    {
        return [
            (object)[
                'var'         => 'hook_sales',
                'item'        => "Hook for #sales",
                'description' => "Enter the Hook URL for posts to the Sales channel",
                'default'     => '',
                'protected'   => false
            ],
            (object)[
                'var'         => 'hook_support',
                'item'        => "Hook for #support",
                'description' => "Enter the Hook URL for posts to the Support channel",
                'default'     => '',
                'protected'   => false
            ],

            (object)[
                'var'         => 'hook_finance',
                'item'        => "Hook for #finance",
                'description' => "Enter the Hook URL for posts to the Finance channel",
                'default'     => '',
                'protected'   => false
            ],
        ];
    }
    /** -------------------- End of Configuration ---------------------- */

    /**
     * Send message to support
     * @param string $message
     * @param array  $complex
     * @return void
     * @throws GuzzleException
     */
    public function sendSupport(string $message, array $complex = []): void
    {
        $this->iSlack->slackHook($this->config->hook_support, $message, $complex);
    }

    /**
     * Send message to accounting
     * @param string $message
     * @param array  $complex
     * @return void
     * @throws GuzzleException
     */
    public function sendAccounting(string $message, array $complex = []): void
    {
        $this->iSlack->slackHook($this->config->hook_finance, $message, $complex);
    }

    /**
     * Send message to sales.
     * @param string $message
     * @param array  $complex
     * @return void
     * @throws GuzzleException
     */
    public function sendSales(string $message, array $complex = []): void
    {
        $this->iSlack->slackHook($this->config->hook_sales, $message, $complex);
    }
}
