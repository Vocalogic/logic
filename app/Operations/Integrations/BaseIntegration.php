<?php

namespace App\Operations\Integrations;

use App\Enums\Core\IntegrationRegistry;
use App\Models\Integration as IntegrationModel;

/**
 * Shared helpers for all integrations, in order to
 * get the integration bootstrapped for usage.
 */
abstract class BaseIntegration
{
    public IntegrationRegistry $ident;
    public object $config;

    /**
     * Setup and do boot checking for integration
     */
    public function __construct()
    {
        $this->checkExistence();
        $this->validateRequirements();
        $this->setConfig();
    }

    /**
     * Make sure the integration is registered in the database so we can track
     * the enablement, and configuration parameters.
     * @return void
     */
    private function checkExistence() : void
    {
        $i = IntegrationModel::where('ident', $this->ident->value)->first();
        if (!$i)
        {
            $reqs = $this->getRequired();
            $data = (object)[];
            foreach ($reqs as $req)
            {
                $data->{$req->var} = $req->default;
            }
            (new IntegrationModel)->create([
                'ident'   => $this->ident->value,
                'enabled' => false,
                'data'    => $data
            ]);
        }
    }

    /**
     * Abstract Override for Getting Required
     * @return array
     */
    public function getRequired(): array
    {
        return [];
    }

    /**
     * Checks to see that we have all of our object presets
     * and the existence of any new ones. Also, removes any
     * that are not in our registry any longer.
     * @return void
     */
    private function validateRequirements() : void
    {
        $i = IntegrationModel::where('ident', $this->ident->value)->first();
        $reqs = $this->getRequired();
        $exists = (object) $i->data ?? (object) [];
        // Scan for new additions.
        foreach ($reqs as $req)
        {
            if (!isset($exists->{$req->var}))
            {
                $exists->{$req->var} = $req->default ?: '';
            }
        }

        // Now scan for existence but no longer required
        foreach (get_object_vars($exists) as $key => $item)
        {
            $found = false;
            foreach ($reqs as $req)
            {
                if ($req->var == $key) $found = true;
            }
            if (!$found) unset($exists->$key);
        }
        // Resave.
        $i->update(['data' => $exists]);
    }

    /**
     * Set Configuration Object
     * @return void
     */
    private function setConfig() : void
    {
        $i = IntegrationModel::where('ident', $this->ident->value)->first();
        $this->config = (object) $i->data;
    }

}
