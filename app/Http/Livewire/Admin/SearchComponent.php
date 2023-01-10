<?php

namespace App\Http\Livewire\Admin;

use App\Enums\Core\CommKey;
use App\Models\Account;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Redirector;

class SearchComponent extends Component
{
    public string $query = '';

    public array $recentActions = [];
    public array $results       = [];


    /**
     * Get any recently viewed accounts, leads, etc and apply them to our
     * recent actions.
     * @return void
     */
    private function getRecentActions() : void
    {
        $recents = session(CommKey::AdminSearchSession->value);
        if (!$recents) $recents = [];
        $this->recentActions = $recents;
    }


    /**
     * Render Search Component
     * @return View
     */
    public function render(): View
    {
        $this->emit('openSearch');
        $this->getRecentActions();
        $this->search();
        return view('admin.partials.core.search');
    }

    /**
     * Clicking an index in a result array and storing
     * that object in our session cache with title
     * @param int $idx
     * @return Redirector
     */
    public function sendTo(int $idx): Redirector
    {
        $obj = (object)$this->results[$idx];
        $stored = session(CommKey::AdminSearchSession->value);
        if (!$stored) $stored = [];
        $found = false;
        foreach ($stored as $store)
        {
            if ($store->url == $obj->url) $found = true;
        }
        if (!$found)
        {
            $stored[] = $obj;
        }
        if (count($stored) > 4)
        {
            // Lets remove the first element.
            array_shift($stored);
        }
        session([CommKey::AdminSearchSession->value => $stored]);
        return redirect()->to($obj->url);
    }


    /**
     * Perform Search Aggregator
     * @return void
     */
    public function search() : void
    {
        if (!$this->query)
        {
            $this->results = [];
            return;
        }
        $this->results = [];
        // Search Leads
        foreach (Lead::where('active', true)
                     ->where(function ($q) {
                         $q->where('company', 'like', "$this->query%");
                         $q->orWhere('contact', 'like', "$this->query%");
                     })->get() as $lead)
        {
            $this->results[] = (object)[
                'title'       => "(LEAD) $lead->company",
                'url'         => "/admin/leads/$lead->id",
                'class'       => 'bg-primary text-white',
                'description' => "Lead #{$lead->id} created on " . $lead->created_at->format("m/d/y") .
                    " last updated on " . $lead->updated_at->format("m/d/y") .
                    " (" . $lead->updated_at->diffForHumans() . ")"
            ];
        }

        // Search Accounts
        foreach (Account::where('active', true)
                     ->with('admin')
                     ->where(function ($q) {
                         $q->where('name', 'like', "$this->query%");
                         $q->orWhereHas('admin', function ($x) {
                             $x->where('name', 'like', "$this->query%");
                         });
                     })->get() as $account)
        {
            if (!$account->admin) continue;
            $this->results[] = (object)[
                'title'       => "$account->name",
                'url'         => "/admin/accounts/$account->id",
                'class'       => 'bg-secondary text-white',
                'description' => "Account #{$account->id} created on " . $account->created_at->format("m/d/y") .
                    " bills $" . number_format($account->mrr, 2) . " monthly."
            ];
        }


    }

}
