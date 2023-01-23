<?php

namespace App\Operations\API\Discord;

use App\Enums\Core\ChatChannel;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class DiscordCore
{

    public object $config;
    public Client $client;
    public string $baseUrl         = "https://discord.com/api/v6/channels/";
    public string $permissionScope = '534723950656'; // Allow all text operations in channels.

    /**
     * Instantiate a new Discord object
     * @param object $config
     */
    public function __construct(object $config)
    {
        $this->config = $config;
        $this->client = new Client();
    }

    public function send(ChatChannel $channel, string $message): void
    {
        $channel = match ($channel)
        {
            ChatChannel::Sales => $this->config->channel_sales,
            ChatChannel::Accounting => $this->config->channel_accounting,
            ChatChannel::Support => $this->config->channel->support
        };
        $token = $this->config->bot_token;
        $pubKey = $this->config->public_key; // Not sure if this is used, but here just in case.
        $headers = [
            'Content-Type'   => 'application/json',
            'Content-Length' => strlen($message),
            'Authorization'  => 'Bot ' . $token
        ];
        try {
            $this->client->post($this->baseUrl . $channel . "/messages", [
                'headers' => $headers,
                'json'    => [
                    'content' => strip_tags($message)
                ]
            ]);
        } catch(Exception $e)
        {
            info("Unable to send to discord: " . $e->getMessage());
        }

    }

    /**
     * Returns the URL to Grant Authorization to a Discord Account/Application
     * @return string
     */
    public function getRedirectUrl() : string
    {
        return sprintf("https://discord.com/api/oauth2/authorize?client_id=%s&permissions=%s&scope=bot",
            $this->config->application_id, $this->permissionScope);
    }

    /**
     * Discord callback - Not used
     * @param Request $request
     * @return void
     */
    public function processCallback(Request $request) : void
    {
        // There is no callback for authorization. Just auth's the bot token.
    }
}
