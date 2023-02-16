<?php

namespace App\Http\Livewire\Iterators;

use App\Http\Livewire\Admin\LwTableComponent;
use App\Models\Quote;

class QuoteIteratorComponent extends LwTableComponent
{
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
        return $collection->where('archived', false);
    }

}
