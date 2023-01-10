<?php

namespace App\Observers;

use App\Enums\Core\IntegrationType;
use App\Models\User;
use App\Operations\Integrations\Support\Support;

class UserObserver
{
    static public bool $running = false;    // Prevents nested observer calls.

    /**
     * Executed when a user is created
     * @param User $user
     * @return void
     */
    public function created(User $user): void
    {
        if (self::$running) return;
        self::$running = true;
        // Update Merchant Provider
        if(hasIntegration(IntegrationType::Support))
        {
            Support::syncUser($user);
        }

        self::$running = false;
    }

    /**
     * Executed when a user is updated.
     * @param User $user
     * @return void
     */
    public function updated(User $user): void
    {
        if (self::$running) return;
        self::$running = true;
        // Update Merchant Provider
        if(hasIntegration(IntegrationType::Support))
        {
            Support::syncUser($user);
        }
    }


}
