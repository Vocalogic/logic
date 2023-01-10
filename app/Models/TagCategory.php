<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $tags
 */
class TagCategory extends Model
{
    protected $guarded = ['id'];


    /**
     * Tags all associated to this category
     * @return HasMany
     */
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class, 'tag_category_id');
    }

    /**
     * Tag categories or headers belong to a category.
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BillCategory::class, 'bill_category_id');
    }
}
