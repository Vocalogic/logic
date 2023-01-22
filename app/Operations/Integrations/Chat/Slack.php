<?php

namespace App\Operations\Integrations\Chat;

use App\Enums\Core\ChatChannel;
use App\Enums\Core\IntegrationRegistry;
use App\Exceptions\LogicException;
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
        return "/assets/images/integrations/slack.png";
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
                'item'        => "Hook for Sales",
                'description' => "Enter the Hook URL for posts to the Sales channel",
                'default'     => '',
                'protected'   => false
            ],
            (object)[
                'var'         => 'hook_support',
                'item'        => "Hook for Support",
                'description' => "Enter the Hook URL for posts to the Support channel",
                'default'     => '',
                'protected'   => false
            ],

            (object)[
                'var'         => 'hook_accounting',
                'item'        => "Hook for Accounting",
                'description' => "Enter the Hook URL for posts to the Accounting channel",
                'default'     => '',
                'protected'   => false
            ],
        ];
    }
    /** -------------------- End of Configuration ---------------------- */

    /**
     * Send a message on a Slack Channel
     * @param ChatChannel $channel
     * @param             $message
     * @return void
     * @throws GuzzleException
     * @throws LogicException
     */
    public function send(ChatChannel $channel, $message): void
    {
        $hook = match ($channel)
        {
            ChatChannel::Accounting => $this->config->hook_accounting,
            ChatChannel::Support => $this->config->hook_support,
            ChatChannel::Sales => $this->config->hook_sales
        };
        $this->iSlack->slackHook($hook, $message);
    }
}
