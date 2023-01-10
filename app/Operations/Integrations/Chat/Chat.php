<?php

namespace App\Operations\Integrations\Chat;

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
     * @param string $message
     * @param array  $complex
     * @return void
     */
    public function sendSupport(string $message, array $complex = []): void
    {
        foreach (IntegrationRegistry::enabledByCategory($this->type) as $case)
        {
            $class = $case->getIntegration();
            $x = new $class();
            $x->sendSupport($message, $complex);
        }
    }

    /**
     * Send to accounting channel
     * @param string $message
     * @param array  $complex
     * @return void
     */
    public function sendAccounting(string $message, array $complex = []): void
    {
        foreach (IntegrationRegistry::enabledByCategory($this->type) as $case)
        {
            $class = $case->getIntegration();
            $x = new $class();
            $x->sendAccounting($message, $complex);
        }
    }

    /**
     * Send to Sales Room
     * @param string $message
     * @param array  $complex
     * @return void
     */
    public function sendSales(string $message, array $complex = []): void
    {
        foreach (IntegrationRegistry::enabledByCategory($this->type) as $case)
        {
            $class = $case->getIntegration();
            $x = new $class();
            $x->sendSales($message, $complex);
        }
    }
}
