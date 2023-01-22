<?php

namespace App\Operations\Integrations\Chat;

use App\Enums\Core\ChatChannel;

/**
 * All Chat integrations must implement this
 */
interface ChatInterface
{
    /**
     * Send a message to a chat integration
     * @param ChatChannel $channel
     * @param string      $message
     * @return void
     */
    public function send(ChatChannel $channel, string $message): void;

}
