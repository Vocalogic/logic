<?php

namespace App\Operations\Integrations;


interface Integration
{
    /**
     * Get the official name of the integration/company
     * @return string
     */
    public function getName(): string;


    /**
     * Get the website for the integration
     * @return string
     */
    public function getWebsite(): string;

    /**
     * Get the description of the integration
     * @return string
     */
    public function getDescription(): string;

    /**
     * Return a logo to be used during activations.
     * @return string
     */
    public function getLogo(): string;

    /**
     * This should be an array of all the items required to use
     * the integration.
     *  - var
     *  - item
     *  - description
     *  - default
     *  - value
     *  - protected (true/false)
     * @return array
     */
    public function getRequired(): array;

}
