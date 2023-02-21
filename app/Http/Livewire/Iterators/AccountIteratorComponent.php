<?php

namespace App\Http\Livewire\Iterators;

use App\Http\Livewire\Admin\LwTableComponent;
use App\Models\Account;

class AccountIteratorComponent extends LwTableComponent
{
    /**
     * Define text array headers for our table.
     * @var array
     */
    public array $headers = [
        'Company'     => ['name'],
        'Agent'       => ['agent_id', 'agent', 'name'],
        'MRR'         => ['mrr|computed'],
        'Outstanding' => ['account_balance|computed'],
        'Next Bill'   => ['next_bill'],
        'States'      => []
    ];

    /**
     * Define how to render a single row entry in html.
     * @var string
     */
    public string $entity = 'admin.accounts.entity';

    /**
     * Define the type of model we are using.
     */
    public string $model = Account::class;

    /**
     * By default we only want active accounts being shown.
     * @param $collection
     * @return mixed
     */
    public function preFilters($collection): mixed
    {
        $collection = $collection->with(['invoices', 'invoices.items', 'invoices.transactions', 'items', 'items.addons', 'agent']);
        $collection = $collection->where('id', '>', 1); // Don't show admin account.
        $req = app('request');
        if ($req->show == 'mrr')
        {
            $collection = $collection->where('active', true)->has('items');
        }
        elseif($req->show =='nrc')
        {
            $collection = $collection->where('active', true)->doesntHave('items');
        }
        elseif($req->show == 'inactive')
        {
            $collection = $collection->where('active', false);
        }
        else
        {
            $collection = $collection->where('active', true);
        }
        return $collection;
    }


}
