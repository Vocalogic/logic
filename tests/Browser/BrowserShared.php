<?php

namespace Tests\Browser;

use App\Enums\Core\ACL;
use App\Models\Account;
use App\Models\EmailTemplate;
use App\Models\User;

class BrowserShared
{
    /**
     * Working User
     * @var User
     */
    static User $user;

    static public function setupInstallation() : void
    {
        User::$disableTFA = true;
        if (setting('brand.name'))
        {
            self::$user = User::find(1);
            return;
        }
        $account = (new Account)->create([
            'name'   => "ACME IT Professionals",
            'active' => 1
        ]);
        // Create admin user.
        $user = (new User)->create([
            'name'       => "Test Admin",
            'email'      => "test@test.com",
            'password'   => bcrypt("password"),
            'account_id' => $account->id,
            'acl'        => ACL::ADMIN->value,
            'active'     => 1,
        ]);
        $user->update(['account_id' => $account->id]);
        $user->refresh();
        setting('brand.name', "ACME IT Professionals");
        setting('brand.url', "http://localhost:8000");
        EmailTemplate::placeholders();
        self::$user = User::find(1);
    }

}
