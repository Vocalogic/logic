<?php

namespace App\Observers;

use App\Enums\Core\IntegrationType;
use App\Models\BillCategory;
use App\Operations\Integrations\Accounting\Finance;

class CategoryObserver
{
    static public bool $running = false;    // Prevents nested observer calls.


    public function created(BillCategory $category) : void
    {
        if (self::$running) return;
        self::$running = true;

        // Update Finance 3rd Party
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::syncCategory($category);
        }




        self::$running = false;
    }

    public function updated(BillCategory $category): void
    {
        if (self::$running) return;
        self::$running = true;

// Update Finance 3rd Party
        if (hasIntegration(IntegrationType::Finance))
        {
            Finance::syncCategory($category);
        }

        self::$running = false;
    }

}
