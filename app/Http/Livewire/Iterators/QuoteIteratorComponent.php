<?php

namespace App\Http\Livewire\Iterators;

use App\Http\Livewire\Admin\LwTableComponent;
use App\Models\Lead;
use App\Models\Quote;

class QuoteIteratorComponent extends LwTableComponent
{
    public Lead $lead;
    /**
     * Define text array headers for our table.
     * @var array
     */
    public array $headers = [
        '#'       => ['id'],
        'Name'    => ['name'],
        'Age'     => ['age|computed'],
        'MRR'     => ['mrr|computed'],
        'NRC'     => ['nrc|computed'],
        'Term'    => ['term'],
        'Value'   => ['totalValue|computed'],
        'Actions' => []
    ];

    /**
     * Define how to render a single row entry in html.
     * @var string
     */
    public string $entity = 'admin.quotes.entity';

    /**
     * Define the type of model we are using.
     */
    public string $model = Quote::class;

    /**
     * By default only show open quotes.
     * @param $collection
     * @return mixed
     */
    public function preFilters($collection): mixed
    {
        $collection = $collection->with([
            'lead',
            'account',
            'items',
            'services',
            'products',
            'items.item',
            'services.item',
            'products.item'
        ]);
        if (isset($this->lead) && $this->lead->id)
        {
            $collection = $collection->where('lead_id', $this->lead->id);
        }
        return $collection->where('archived', false);
    }

}
