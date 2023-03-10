<?php

namespace App\Http\Livewire\Iterators;

use App\Http\Livewire\Admin\LwTableComponent;
use App\Models\AppLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
     * this is for url query filters
     */
    public array $filter;

    /**
     * The model that was passed through from the controller.
     * @var Model
     */
    public Model $modelEntity;

    /**
     * initialization
     */
    public function mount()
    {
        $this->filter = request()->all();
    }

    /**
     * Filter by dates if found.
     * @param $collection
     * @return mixed
     */
    public function preFilters($collection): mixed
    {
        $collection = $collection->where('type', $this->modelEntity::class);
        $collection = $collection->where('type_id', $this->modelEntity->id);

        if (!empty($this->filter['start_date']))
        {
            $collection = $collection->whereDate('created_at', '>=', Carbon::createFromFormat('m/d/Y', $this->filter['start_date']));
        }
        if (!empty($this->filter['end_date']))
        {
            $collection = $collection->whereDate('created_at', '<=', Carbon::createFromFormat('m/d/Y', $this->filter['end_date']));
        }
        return $collection;
    }


}
