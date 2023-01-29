<?php

namespace App\Operations\Integrations\Backup;

use App\Enums\Core\IntegrationType;

class Backup
{
    public IntegrationType $type = IntegrationType::Backup;

    /**
     * Perform a site backup using the integration selected
     * @return void
     */
    static public function backupSiteData() : void
    {
        $x = new self;
        getIntegration($x->type)->connect()->backupSiteData();

    }

    /**
     * Backup the database using the integration selected.
     * @return void
     */
    static public function backupDatabase(): void
    {
        $x = new self;
        getIntegration($x->type)->connect()->backupDatabase();
    }

}
