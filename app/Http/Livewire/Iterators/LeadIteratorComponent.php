<?php

namespace App\Http\Livewire\Iterators;

use App\Http\Livewire\Admin\LwTableComponent;
use App\Models\Lead;

class LeadIteratorComponent extends LwTableComponent
{
    /**
     * Define text array headers for our table.
     * @var array
     */
    public array $headers = [
        'Company' => ['company'],
        'Contact' => ['contact'],
        'Status'  => ['lead_status_id', 'status', 'name'],
        'Type'    => ['lead_type_id', 'type', 'name'],
        'Age'     => ['age|computed'],
        'Agent'   => ['agent_id', 'agent', 'name'],
        'MRR/NRC' => [],
    ];

    /**
     * Define how to render a single row entry in html.
     * @var string
     */
    public string $entity = 'admin.leads.entity';

    /**
     * Define the type of model we are using.
     */
    public string $model = Lead::class;

    /**
     * Add filters based on actions from the left sidebar
     * @param $collection
     * @return mixed
     */
    public function preFilters($collection): mixed
    {
        $req = app('request');
        if ($req->status)
        {
            $collection = $collection->where('lead_status_id', $req->status);
        }
        else
        {
            $collection = $collection->where('active', true);
        }
        return $collection;
    }
}
