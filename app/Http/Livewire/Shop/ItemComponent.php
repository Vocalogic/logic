<?php

namespace App\Http\Livewire\Shop;

use App\Enums\Core\CommKey;
use App\Models\BillCategory;
use App\Models\BillItem;
use App\Models\BillItemTag;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ItemComponent extends Component
{
    public BillCategory $category;

    public array $filters = [];
    public array $items = [];

    private int $itemsPerPage = 12;
    public int $pages = 1;
    public int $page = 1;

    /**
     * Setup component with filters.
     * @return void
     */
    public function mount(): void
    {
        if (session(CommKey::LocalFilterSession->value))
        {
            $this->filters = session(CommKey::LocalFilterSession->value);
        }
        $this->renderResults();
    }

    /**
     * Output Category View
     * @return View
     */
    public function render(): View
    {
        return view('shop.category.component');
    }

    /**
     * Should this checkbox be checked?
     * @param Tag $tag
     * @return bool
     */
    public function isChecked(Tag $tag) : bool
    {
        return in_array($tag->id, $this->filters);
    }

    /**
     * Toggle filter based on checkbox clicked.
     * @param Tag $tag
     * @return void
     */
    public function toggleFilter(Tag $tag): void
    {
        foreach ($this->filters as $idx => $filter)
        {
            if ($filter == $tag->id)
            {
                unset($this->filters[$idx]);
                session([CommKey::LocalFilterSession->value => $this->filters]);
                $this->renderResults();
                return; // Found, removed

            }
        }
        $this->filters[] = $tag->id;
        session([CommKey::LocalFilterSession->value => $this->filters]);
        $this->renderResults();
    }


    /**
     * Update our items array based on filters presented.
     * @return void
     */
    public function renderResults() : void
    {
        $skip = ($this->page-1) * $this->itemsPerPage;

        if (empty($this->filters))
        {
            $total = BillItem::where('bill_category_id', $this->category->id)->with('category')->where('shop_show', true)->whereNull('parent_id')->count();
            $this->items = BillItem::where('bill_category_id', $this->category->id)->with('category')->where('shop_show', true)->whereNull('parent_id')->skip($skip)->take($this->itemsPerPage)->get()->toArray();
            $this->pages = (int) ceil($total / $this->itemsPerPage);
            if ($this->pages < 1) $this->pages = 1;
            return;
        }
        // We have filters.
        // We only want to show items that contain tags that we have selected.
        $itemTags = BillItemTag::whereIn('tag_id', $this->filters)->get();
        $items = [];
        foreach ($itemTags as $tag)
        {
            $items[] = $tag->bill_item_id;
        }
        $total = BillItem::with('category')->whereIn('id', $items)->whereNull('parent_id')->where('shop_show', true)->count();
        $this->items = BillItem::with('category')->whereIn('id', $items)->whereNull('parent_id')->where('shop_show', true)->skip($skip)->take($this->itemsPerPage)->get()->toArray();
        $this->pages = (int) ceil($total) / $this->itemsPerPage;
        if ($this->pages < 1) $this->pages = 1;
    }

    /**
     * Move forward one page.
     * @return void
     */
    public function forwardPage(): void
    {
        if ($this->page == $this->pages)
        {
            return; // We're at the end can't advance anymore
        }
        $this->page++;
        $this->renderResults();
    }

    /**
     * Move back a page
     * @return void
     */
    public function backPage(): void
    {
        if ($this->page == 1) return;
        $this->page--;
        $this->renderResults();
    }

    /**
     * Set page index
     * @param int $page
     * @return void
     */
    public function setPage(int $page) : void
    {
        $this->page = $page;
        $this->renderResults();
    }


}
