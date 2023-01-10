<?php

namespace App\Http\Livewire\Admin;

use App\Models\Account;
use App\Operations\API\NS\Domain;
use Illuminate\View\View;
use Livewire\Component;

class PbxAssignComponent extends Component
{
    public string $message = 'Loading System Inventory..';

    public Account $account;

    public array $domains = [];

    public function mount()
    {
        $this->getDomains();
    }

    /**
     * Get list of domains
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDomains()
    {
        info("Running");
        $dom = new Domain($this->account->provider);
        $list = $dom->all();
        $this->domains[''] = "-- Select Domain --";
        foreach ($list as $pbx)
        {
            if (Account::where('pbx_domain', $pbx->domain)->count() == 0)
            {
                $this->domains[$pbx->domain] = $pbx->description;
            }
        }

    }

    /**
     * @return View

    public function render(): View
    {
        return view('voip::admin.accounts.pbx.assign_component');
    }
     */

}
