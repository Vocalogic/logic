<?php

namespace App\Operations\Integrations\Chat;

use App\Enums\Core\ChatChannel;
use App\Enums\Core\IntegrationRegistry;
use App\Enums\Core\IntegrationType;

/**
 * Utility Class to connect to the chat implmentations enabled
 * and to execute.
 */
class Chat implements ChatInterface
{
    public IntegrationType $type = IntegrationType::Chat;

    /**
     * Send to all chat integrations enabled.
     * @param ChatChannel $channel
     * @param string      $message
     * @return void
     */
    public function send(ChatChannel $channel, string $message): void
    {
        foreach (IntegrationRegistry::enabledByCategory($this->type) as $case)
        {
            $class = $case->getIntegration();
            $x = new $class();
            $x->send($channel, $message);
        }
    }


}
