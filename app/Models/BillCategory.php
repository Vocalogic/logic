<?php

namespace App\Models;

use App\Enums\Core\BillItemType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $type
 * @property mixed $finance_category_id
 * @property mixed $name
 * @property mixed $id
 * @property mixed $photo_id
 * @property mixed $shop_offer_image_id
 */
class BillCategory extends Model
{
    protected $guarded = ['id'];

    /**
     * Categories have many items.
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    /**
     * A category has many tag categories.
     * @return HasMany
     */
    public function tagCategories(): HasMany
    {
        return $this->hasMany(TagCategory::class, 'bill_category_id');
    }

    /**
     * Based on the item we are provided, what are the selectable categories
     * @param BillItem $item
     * @return array
     */
    public function getCategoriesByItem(BillItem $item) : array
    {
        $type = $item->type == BillItemType::PRODUCT->value ? 'products' : 'services';
        return BillCategory::where('type', $type)->orderBy('name')->pluck('name', 'id')->all();
    }

}
