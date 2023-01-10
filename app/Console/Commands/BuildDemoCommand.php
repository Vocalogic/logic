<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\BillItemCategoryController;
use App\Models\Account;
use App\Models\BillCategory;
use App\Models\BillItem;
use App\Models\User;
use App\Observers\AccountObserver;
use App\Observers\BillItemObserver;
use App\Observers\CategoryObserver;
use App\Observers\UserObserver;
use App\Operations\Core\MetricsOperation;
use Faker\Factory;
use Illuminate\Console\Command;

class BuildDemoCommand extends Command
{
    const ACCOUNTS = 125;
    const SERVICES = 10;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build Demo Environment with Fake Data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $answer = $this->ask("Are you sure you want to build a demo? (Y to proceed)");
        if ($answer != 'Y') return 0;
        $f = Factory::create();
        // Build Accounts
        $this->info("Building Accounts..");
        foreach (range(1, self::ACCOUNTS) as $num)
        {
            AccountObserver::$running = true; // disable
            $a = (new Account)->create([
                'name'     => $f->company,
                'address'  => $f->streetAddress,
                'city'     => $f->city,
                'state'    => 'GA',
                'postcode' => $f->postcode,
                'country'  => 'US',
                'phone'    => $f->phoneNumber,
                'uuid'     => $f->uuid
            ]);
            UserObserver::$running = true;
            $u = (new User)->create([
                'name'       => $f->name,
                'email'      => $f->email,
                'password'   => bcrypt($f->password),
                'acl'        => 'ADMIN',
                'active'     => true,
                'phone'      => $f->phoneNumber,
                'account_id' => $a->id
            ]);
        }

        // Build Services Categories and Products
        $this->info("Building Service Categories..");
        foreach (range(1, 5) as $num)
        {
            BillItemObserver::$running = true;
            CategoryObserver::$running = true;
            (new BillCategory)->create([
                'name'        => ucfirst($f->word) . " Services",
                'description' => $f->sentence,
                'type'        => 'services'
            ]);
        }
        $this->info("Building Product Categories..");
        foreach (range(1, 5) as $num)
        {
            BillItemObserver::$running = true;
            CategoryObserver::$running = true;
            (new BillCategory)->create([
                'name'        => ucfirst($f->word) . " Products",
                'description' => $f->sentence,
                'type'        => 'products',
            ]);
        }



        $this->info("Creating Sample Services...");
        foreach (range(1, self::SERVICES) as $num)
        {
            (new BillItem)->create([
                'bill_category_id' => $this->getCategory('services'),
                'code' => "SVC-" . strtoupper($f->word),
                'name' => $f->sentence,
                'type' => 'services',
                'description' => $f->sentence(20),
                'mrc' => rand(2, 150),
                'ex_opex' => rand(2, 10),
                'ex_opex_once' => false,
            ]);
        }
        $this->info("Creating Sample Products...");

        foreach (range(1, self::SERVICES) as $num)
        {
            (new BillItem)->create([
                'bill_category_id' => $this->getCategory('products'),
                'code' => "PR-" . strtoupper($f->word),
                'name' => $f->sentence,
                'type' => 'products',
                'description' => $f->sentence(20),
                'nrc' => rand(2, 150),
                'ex_capex' => rand(2, 10),
                'ex_capex_once' => false,
            ]);
        }

        $this->info("Assigning Services to Sample Accounts...");

        foreach(Account::all() as $account)
        {
            foreach(range(1, rand(1,10)) as $num)
            {
                $item = $this->getItem('services');
                $account->items()->create([
                    'bill_item_id' => $item->id,
                    'description' => $item->description,
                    'price' => $item->mrc,
                    'qty' => rand(1,10),
                ]);
            }
        }

        // Lead Generation



        MetricsOperation::run();


        return 0;
    }

    private function getCategory(string $string)
    {
        $cat = BillCategory::where('type', $string)->inRandomOrder()->first();
        return $cat->id;
    }

    private function getItem(string $type)
    {
        $item = BillItem::where('type', $type)->inRandomOrder()->first();
        return $item;
    }
}
