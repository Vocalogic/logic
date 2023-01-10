<?php

namespace App\Operations\Integrations\Chat;

/**
 * All Chat integrations must implement this
 */
interface ChatInterface
{
    /**
     * Send a message to support
     * @param string $message
     * @param array  $complex
     * @return void
     */
    public function sendSupport(string $message, array $complex = []): void;

    /**
     * Send a message to Accounting
     * @param string $message
     * @param array  $complex
     * @return void
     */
    public function sendAccounting(string $message, array $complex = []): void;


    /**
     * Send a message to sales.
     * @param string $message
     * @param array  $complex
     * @return void
     */
    public function sendSales(string $message, array $complex = []): void;

}
