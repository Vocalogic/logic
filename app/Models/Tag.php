<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @property mixed $id
 */
class Tag extends Model
{
    protected $guarded = ['id'];

    /**
     * A tag belongs to a category
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TagCategory::class, 'tag_category_id');
    }

    /**
     * Get number of items that have this tag. Only count primary items
     * and if their shop show is set.
     * @return int
     */
    public function getCountAttribute(): int
    {
        return BillItemTag::with('item')->where('tag_id', $this->id)->whereHas('item', function ($q) {
            $q->where('shop_show', true);
            $q->where('parent_id', null);
        })->count();
    }

    /**
     * Return a list of tags sorted by their category
     * @param BillCategory|null $category
     * @return array
     */
    static public function selectable(?BillCategory $category = null): array
    {
        $data = [];
        $data[''] = '-- Select Tag --';
        if ($category)
        {
            $tagcats = TagCategory::orderBy('name')->where('bill_category_id', $category->id)->get();
        }
        else
        {
            $tagcats = TagCategory::orderBy('name')->get();
        }
        foreach ($tagcats as $cat)
        {
            $block = [];
            foreach ($cat->tags as $tag)
            {
                $block[$tag->id] = $tag->name;
            }
            $data[$cat->name] = $block;
        }
        return $data;
    }


    /**
     * This will safely remove the tag, remove all its bindings with products, etc.
     * @return void
     */
    public function remove(): void
    {
        BillItemTag::where('tag_id', $this->id)->delete();
        $this->delete();
    }


}
