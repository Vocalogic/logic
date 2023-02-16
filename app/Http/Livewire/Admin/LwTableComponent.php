<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Component;


abstract class LwTableComponent extends Component
{
    public array      $headers;                     // Headers for Table
    public array      $rows;                        // Rows sent to entity view
    public Collection $collection;                  // Working Collection for Filters
    public string     $entity;                      // The entity view
    public int        $rowsPerPage  = 15;           // Max rows per page
    public string     $search       = '';           // Search string binding
    public string     $model;                       // Model Class
    public string     $sortBy       = '';           // Sort by field?
    public bool       $sortAsc      = true;         // Sort Ascending?
    public bool       $prevDisabled = false;        // Previous Button Enabled?
    public bool       $nextDisabled = false;        // Next button enabled?
    public int        $maxRecords;                  // Max records before filtering
    public int        $maxPages;                    // Max pages for pagination
    public int        $activePage   = 1;            // What page are we viewing?

    /**
     * Prefilters are to be done before any searching, things like setting
     * active states, etc.
     * @param $collection
     * @return mixed
     */
    public function preFilters($collection): mixed
    {
        return $collection;
    }

    /**
     * If we are sorting or searching on computed properties. This is called
     * after the get() and allows us to do further iteration on the attributes.
     * @param $collection
     * @return mixed
     */
    public function postFilters($collection): mixed
    {
        return $collection;
    }

    public function sortCollection($collection): mixed
    {
        if ($this->sortBy)
        {
            $collection = $this->sortAsc ? $collection->sortBy($this->sortBy) : $collection->sortByDesc($this->sortBy);
        }
        return $collection;
    }

    /**
     * Sort a column
     * @param string $key
     * @return void
     */
    public function sort(string $key): void
    {
        if (preg_match("/\|/", $key))
        {
            $x = explode("|", $key);
            $key = $x[0];
        }
        if ($this->sortBy == $key)
        {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $key;
    }

    /**
     * When searching, paginating, etc, these will be applied
     * here.
     * @return void
     */
    public function filters(): void
    {
        $collection = new $this->model;
        $collection = $this->preFilters($collection);
        if ($this->search)
        {
            $collection = $collection->where(function ($q) {
                foreach ($this->headers as $header => $data)
                {
                    if (sizeOf($data) == 1) // Just a basic filter during the collection phase.
                    {
                        if (!preg_match("/\|/", $data[0])) // not computed just normal
                        {
                            $q->orWhere($data[0], 'like', "%$this->search%");
                        }
                    }
                    elseif (sizeOf($data) == 3)
                    {
                        // This is a relationship
                        $q->orWhereHas($data[1], function ($q) use ($data) {
                            $q->where($data[2], 'like', "%$this->search%");
                        });
                    }
                }
            });
        }
        $this->maxRecords = $collection->count();
        $this->maxPages = (int) ceil($this->maxRecords / $this->rowsPerPage);
        $collection = $collection->take($this->rowsPerPage);
        $active = $this->activePage - 1; // For skipping we want page 1 to be 0 so it skips nothing.
        $skipRecords = $active * $this->rowsPerPage;
        $collection = $collection->skip($skipRecords);
        $collection = $collection->get();
        $this->collection = $this->postFilters($collection);
        $this->collection = $this->sortCollection($collection);
    }


    /**
     * Manage the state of the pagination
     * @return void
     */
    public function paginationButtons(): void
    {
        $this->prevDisabled = $this->activePage == 1;
        $this->nextDisabled = $this->activePage == $this->maxPages;
    }

    /**
     * Move our page to the left.
     * @return void
     */
    public function prev(): void
    {
        $this->activePage--;
        if ($this->activePage == 0) $this->activePage = 1; // Just in case
    }

    /**
     * Move our page to the right
     * @return void
     */
    public function next(): void
    {
        $this->activePage++;
        if ($this->activePage >= $this->maxPages) $this->activePage = $this->maxPages;
    }

    /**
     * Set Active Page
     * @param int $page
     * @return void
     */
    public function toPage(int $page)
    {
        $this->activePage = $page;
    }

    /**
     * Filter, Paginate and render the component
     * @return View
     */
    public function render(): View
    {
        $this->filters();
        $this->paginationButtons();
        return view('admin.partials.core.lw_table');
    }


}
