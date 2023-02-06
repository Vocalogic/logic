<?php

namespace App\Models;

use App\Enums\Core\BillFrequency;
use App\Enums\Core\BillItemType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\DisallowedRawHtml\DisallowedRawHtmlExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\MarkdownConverter;

/**
 * @property mixed        $feature_list
 * @property mixed        $mrc
 * @property mixed        $nrc
 * @property mixed        $ex_capex
 * @property mixed        $ex_opex
 * @property mixed        $type
 * @property mixed        $id
 * @property mixed        $description
 * @property mixed        $finance_item_id
 * @property mixed        $name
 * @property mixed        $category
 * @property mixed        $code
 * @property mixed        $allowed_type
 * @property mixed        $allowed_qty
 * @property mixed        $allowed_overage
 * @property mixed        $frequency
 * @property mixed        $lid
 * @property mixed        $marketing_description
 * @property int|mixed    $qty
 * @property array|mixed  $addonsSelected
 * @property mixed        $tags
 * @property mixed        $slick_id
 * @property mixed        $msrp
 * @property mixed        $addons
 * @property mixed        $price
 * @property mixed|string $uid
 * @property int|mixed    $addonTotal
 * @property bool|mixed   $canUpdateQty
 * @property false|mixed  $couponApplied
 * @property int|mixed    $discountedAmount
 * @property mixed        $track_qty
 * @property mixed        $on_hand
 * @property mixed $allow_backorder
 * @property mixed $children
 * @property mixed $variation_category
 * @property mixed $parent
 * @property mixed $term_meta
 * @property mixed       $priceBeforeContract
 * @property mixed       $reservation_mode
 * @property mixed       $reservation_price
 * @property mixed       $cartMeta
 * @property mixed       $meta
 * @property mixed       $slug
 * @property mixed       $photo_id
 * @property mixed       $photo_2
 * @property mixed       $photo_3
 * @property mixed       $photo_4
 * @property mixed       $photo_5
 * @property float|mixed $min_price
 * @property float|mixed $max_price
 * @property mixed       $confirmation_dialog
 */
class BillItem extends Model
{
    protected $guarded = ['id'];
    public    $casts   = [
        'frequency'    => BillFrequency::class
    ];

    protected $appends = [
        'discountTerm'
    ];

    /**
     * An item belongs to a category.
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BillCategory::class, 'bill_category_id');
    }

    /**
     * A bill item can have many metadata requirements.
     * @return HasMany
     */
    public function meta(): HasMany
    {
        return $this->hasMany(BillItemMeta::class);
    }

    /**
     * Return a split array of feature items.
     * @return array
     */
    public function getFeatureArrayAttribute(): array
    {
        return explode("\n", $this->feature_list);
    }


    /**
     * An item can be bound to a terms of service.
     * @return BelongsTo
     */
    public function terms(): BelongsTo
    {
        return $this->belongsTo(Term::class, 'tos_id');
    }

    /**
     * Get profit based on op/cap
     * @return float
     */
    public function getProfitAttribute(): float
    {
        return match ($this->type)
        {
            BillItemType::PRODUCT->value => $this->nrc - $this->ex_capex,
            BillItemType::SERVICE->value => $this->mrc - $this->ex_opex,
            default => 0
        };
    }

    /**
     * A bill item can have many addons.
     * @return HasMany
     */
    public function addons(): HasMany
    {
        return $this->hasMany(Addon::class, 'bill_item_id');
    }

    /**
     * A bill item can have many tags.
     * @return HasMany
     */
    public function tags(): HasMany
    {
        return $this->hasMany(BillItemTag::class, 'bill_item_id');
    }

    /**
     * Is this a variation?
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BillItem::class, 'parent_id');
    }

    /**
     * Does this item have variations?
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(BillItem::class, 'parent_id');
    }

    /**
     * A Bill Item has many faqs
     * @return HasMany
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(BillItemFaq::class, 'bill_item_id');
    }

    /**
     * Render markup for full description
     * @return string
     */
    public function getFullAttribute(): string
    {
        return $this->marketing_description ?: $this->description;
    }

    /**
     * Get all services as selectable array
     * @return array
     */
    static public function serviceSelectable(): array
    {
        $opts = [];
        $opts[] = "-- Select Item --";
        $cats = BillCategory::orderBy('name')->where('type', 'services')->get();
        foreach ($cats as $cat)
        {
            $block = [];
            foreach ($cat->items as $i)
            {
                $block[$i->id] = "[$i->code] $i->name";
            }
            $opts[$cat->name] = $block;
        }
        return $opts;
    }

    /**
     * Get all products as selectable array
     * @return array
     */
    static public function productSelectable(): array
    {
        $opts = [];
        $opts[] = "-- Select Item --";
        $cats = BillCategory::orderBy('name')->where('type', 'products')->get();
        foreach ($cats as $cat)
        {
            $block = [];
            foreach ($cat->items as $i)
            {
                $block[$i->id] = "[$i->code] $i->name";
            }
            $opts[$cat->name] = $block;
        }
        return $opts;
    }


    /**
     * Create a list of items to select from a list based on the type
     * given (product or service)
     * @param BillItem|null $item
     * @return array
     */
    static public function selectable(?BillItem $item = null): array
    {
        $opts = [];
        $opts[] = "-- Select Item --";
        if ($item)
        {
            $type = $item->type == BillItemType::PRODUCT->value ? 'products' : 'services';
            $cats = BillCategory::where('type', $type)->orderBy('name')->get();
        }
        else
        {
            $cats = BillCategory::orderBy('name')->get();
        }
        foreach ($cats as $cat)
        {
            $block = [];
            foreach ($cat->items as $i)
            {
                $block[$i->id] = "[$i->code] $i->name";
            }
            $opts[$cat->name] = $block;
        }
        return $opts;
    }

    /**
     * Return an array of items that have the same relatable
     * tag as the item given.
     * @return array
     */
    public function getRelatedItems(): array
    {
        // Get relatable tags.
        $relatables = [];
        foreach ($this->tags as $tag)
        {
            if ($tag->tag && $tag->tag->relatable)
            {
                $relatables[] = $tag->tag->id;
            }
        }
        // We now have all tags for this item that have a relatable flag.
        $data = [];
        $list = BillItemTag::whereIn('tag_id', $relatables)->get();
        // These are all the bill items that relatable tags.
        foreach ($list as $i)
        {
            if (in_array($i->item_id, $data)) continue;
            if ($this->id != $i->bill_item_id)
            {
                $data[$i->bill_item_id] = $i->item;
            }
        }
        return $data;
    }

    /**
     * Get percentage from MSRP for discount
     * @return float
     */
    public function getPercAttribute(): float
    {
        if (!$this->msrp) return 0;
        $base = $this->type == BillItemType::PRODUCT->value ? $this->nrc : $this->mrc;
        return 100 - round($base / $this->msrp * 100);
    }

    /**
     * Get the Variation Category this item relates to.
     * @return string
     */
    public function getVariationCategoryNameAttribute(): string
    {
        if ($this->variation_category)
        {
            return $this->variation_category;
        }
        else return $this->parent->variation_category;
    }

    /**
     * Return a html popover for item variants for admin
     * @return string
     */
    public function getVariantExportAttribute(): string
    {
        $data = "<table><tbody>";
        foreach ($this->children as $child)
        {
            $data .= "<tr><td><a href='/admin/category/{$child->category->id}/items/$child->id'>$child->name ({$child->variation_name})</a></td></tr>";
        }
        $data .= "</table>";
        return $data;
    }

    /**
     * Get Margin based on MSRP Settings
     * @return int
     */
    public function getMarginAttribute() : int
    {
        $price = $this->type == BillItemType::PRODUCT->value ? $this->nrc : $this->mrc;
        $exp = $this->type == BillItemType::PRODUCT->value ? $this->ex_capex : $this->ex_opex;
        if (!$exp) return 100;
        return (int) round( ($price - $exp) / $price * 100);
    }


    /**
     * Change a particular discount for a term
     * @param int   $term
     * @param float $discountAmount
     * @return void
     */
    public function changeDiscountTerm(int $term, float $discountAmount): void
    {
        $this->initTermDiscounts();
        $this->refresh();
        $obj = json_decode($this->term_meta);
        $term = (string)$term;
        $obj->{$term} = $discountAmount;
        $this->update(['term_meta' => json_encode($obj)]);
    }

    /**
     * Get discount for a particular term for a bill item.
     * @return object
     */
    public function getDiscountTermAttribute(): object
    {
        if (!$this->term_meta)
        {
            $this->initTermDiscounts();
            $this->refresh();
        }
        return json_decode($this->term_meta);
    }



    /**
     * Get price for product or service based on the price given
     * and a contract term.
     * @param float  $price
     * @param string $term
     * @return float
     */
    public function discountTermValue(float $price, string $term): float
    {
        $obj = $this->getDiscountTermAttribute();
        $perc = $obj->{$term};
        $discountAmount = $price * ($perc / 100);
        return $price - $discountAmount;
    }


    /**
     * Used to sync up our current available terms with proper objects.
     * This will not auto-refresh the BillItem and you will need to
     * refresh() manually after this is updated.
     * @return void
     */
    private function initTermDiscounts(): void
    {
        $i = BillItem::find($this->id);
        $meta = $i->term_meta;
        if (!$meta)
        {
            $meta = (object)[];
        }
        else $meta = json_decode($meta);
        $termList = setting('quotes.terms');
        $list = explode(",", $termList);
        foreach ($list as $opt)
        {
            $opt = trim($opt); // Remove spaces
            if (!isset($meta->{$opt}))
            {
                $meta->{$opt} = 0;
                $i->update(['term_meta' => json_encode($meta)]);
            }
        }
    }


}
