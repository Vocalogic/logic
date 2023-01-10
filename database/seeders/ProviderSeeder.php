<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addProvider("DialPath", "www.dialpath.com", "https://www.dialpath.com/images/logo.png",
            "https://manage.dialpath.com/ns-api");

        $this->addProvider("RingLogix", "www.ringlogix.com", "https://events.channelpronetwork.com/sites/default/files/ringlogix-full-color%402x.png",
        "https://core1-mia.ringlogix.com/ns-api");

    }

    /**
     * Add a provider into the mix.
     * @param string      $name
     * @param string      $url
     * @param string|null $logo
     * @param string      $endpoint
     * @return void
     */
    private function addProvider(string $name, string $url, ?string $logo, string $endpoint)
    {
        if (Provider::where('name', $name)->count()) return;
        (new Provider)->create([
            'name'     => $name,
            'website'  => $url,
            'logo'     => $logo,
            'endpoint' => $endpoint,
            'enabled'  => false
        ]);
    }


}
