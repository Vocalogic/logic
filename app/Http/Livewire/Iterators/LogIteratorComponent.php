<?php

namespace App\Http\Livewire\Iterators;

use App\Http\Livewire\Admin\LwTableComponent;
use App\Models\AppLog;

class LogIteratorComponent extends LwTableComponent
{
    /**
     * Define text array headers for our table.
     * @var array|string[]
     */
    public array $headers = [
        'ID'            => ['id'],
        'Date'          => ['created_at'],
        'Account'       => ['account_id', 'account', 'name'],
        'Level'         => ['log_level'],
        'Message'       => ['log'],
        'Details'       => ['detail'],
    ];

    public string $sortBy = 'created_at';
    public bool $sortAsc = false;

    /**
     * Define how to render a single row entry in html.
     * @var string
     */
    public string $entity = 'admin.logs.entity';

    /**
     * Define the type of model we are using.
     */
    public string $model = AppLog::class;

    /**
     * By default we only want active accounts being shown.
     * @param $collection
     * @return mixed
     */
    public function preFilters($collection): mixed
    {
        if (request()->filled('start_date'))
        {
            $collection->where('created_at', '>=', request()->start_date);
        }
        if (request()->filled('end_date'))
        {
            $collection->where('created_at', '<=', request()->end_date);
        }
        return $collection;
    }


}
