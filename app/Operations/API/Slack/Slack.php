<?php

namespace App\Operations\API\Slack;

use App\Operations\API\APICore;
use GuzzleHttp\Exception\GuzzleException;

class Slack extends APICore
{
    /**
     * Send a message via a webhook to slack
     * @param string      $hook
     * @param string|null $simpleMessage
     * @param array|null  $complexMessage
     * @return mixed
     * @throws GuzzleException
     */
    public function slackHook(string $hook, ?string $simpleMessage = null, ?array $complexMessage = []): mixed
    {
        if ($simpleMessage)
        {
            return $this->send($hook, 'post', [
                'text' => $simpleMessage
            ]);
        }
        else
        {
            return $this->send($hook, 'post', $complexMessage);
        }
    }

}
