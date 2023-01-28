<?php

namespace App\Models;

use App\Enums\Core\BillFrequency;
use App\Operations\Admin\AnalysisEngine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed        $allowed_type
 * @property mixed        $allowed_qty
 * @property mixed        $allowed_overage
 * @property mixed        $id
 * @property mixed        $addons
 * @property mixed        $price
 * @property mixed        $item
 * @property mixed        $qty
 * @property int|mixed    $addonTotal
 * @property mixed|string $uid
 * @property mixed        $quote
 * @property mixed        $ord
 * @property mixed        $meta
 */
class QuoteItem extends Model
{
    protected $guarded = ['id'];
    public    $casts   = [
        'frequency' => BillFrequency::class,
        'meta'      => 'json'
    ];

    /**
     * Items belong to a quote.
     * @return BelongsTo
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * A quote item belongs to a billable item
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(BillItem::class, 'item_id');
    }

    /**
     * A quote item can have addons.
     * @return HasMany
     */
    public function addons(): HasMany
    {
        return $this->hasMany(QuoteItemAddon::class, 'quote_item_id');
    }

    /**
     * Based on the item in a quote, project what commissions would look like
     * for MRR.
     * @return int
     */
    public function getCommissionableAttribute(): int
    {
        return AnalysisEngine::byQuoteItem(user(), $this);

    }

    /**
     * Get the total cost of any addons.
     * @return float
     */
    public function getAddonTotalAttribute(): float
    {
        $total = 0;
        foreach ($this->addons as $addon)
        {
            $total += $addon->price * $addon->qty;
        }
        return round($total, 2);
    }

    /**
     * Get Text Representation of Addon for Checkout Page
     * @return string
     */
    public function getAddonSummaryAttribute(): string
    {
        if ($this->addons()->count() == 0) return '';
        $data = [];
        foreach ($this->addons as $addon)
        {
            $data[] = sprintf("%s: %s (+%s)", $addon->option->addon->name, $addon->option->name,
                number_format($addon->price, 2));
        }
        return "<br/>" . implode("<br/>", $data);
    }

    /**
     * How much did you save from the MSRP set.
     * @return float
     */
    public function getSavedAttribute(): float
    {
        return $this->item->msrp - $this->price;
    }

    /**
     * Get a text representation of the qty allowed.
     * @return string
     */
    public function getAllowanceAttribute(): string
    {
        return sprintf("%s Allowed: %d (Overage Rate: $%s p/%s)",
            $this->allowed_type->getHuman(),
            $this->allowed_qty,
            number_format($this->allowed_overage, 3),
            $this->allowed_type->getShort()
        );
    }

    /**
     * When adding a quote item what order should we assign it?
     * This is for new quote items only.
     * @return int
     */
    public function setNewOrd(): int
    {
        $ttl = 0;
        foreach ($this->quote->items as $item)
        {
            if (!$item->item)
            {
                $item->delete();
                continue;
            }
            if ($item->item->type == 'services' && $this->item->type == 'services') $ttl++;
            if ($item->item->type == 'products' && $this->item->type == 'products') $ttl++;
        }
        $ttl++; // Set new advancement.
        return $ttl;
    }


    /**
     * Can this item be moved down on the associated list?
     * @return bool
     */
    public function canMoveDown(): bool
    {
        if ($this->item->type == 'services')
        {
            $count = $this->quote->services()->count();
        }
        else
        {
            $count = $this->quote->products()->count();
        }
        if ($count <= 1) return false; // only on there can't move anywhere.
        if ($this->ord == $count) return false;
        return true;

    }

    /**
     * Can the item be moved up on the associated list?
     * @return bool
     */
    public function canMoveUp(): bool
    {
        if ($this->item->type == 'services')
        {
            $count = $this->quote->services()->count();
        }
        else
        {
            $count = $this->quote->products()->count();
        }
        if ($count <= 1) return false;     // only one there can't move anywhere.
        if ($this->ord == 1) return false; // at the top already.
        return true;
    }

    /**
     * Move an item up in the list (dec ORD)
     * @return void
     */
    public function moveUp(): void
    {
        $this->update(['ord' => $this->ord - 2]); // 2 to overtake previous position.
        $this->quote->reord();
    }

    /**
     * Move an item down (inc ORD)
     * @return void
     */
    public function moveDown(): void
    {
        $this->update(['ord' => $this->ord + 2]);
        $this->quote->reord();
    }


    /**
     * Get metadata answer for a requirement.
     * @param BillItemMeta $meta
     * @param int|null     $qtyIndex
     * @return string|null
     */
    public function getMetaFor(BillItemMeta $meta, ?int $qtyIndex = null): ?string
    {
        $existing = $this->meta;
        if ($qtyIndex == null)
        {
            if (!is_array($existing) || !array_key_exists($meta->id, $existing)) return null;
            return $existing[$meta->id];
        }
        else
        {
            if (!is_array($existing) || !array_key_exists($meta->id . "_" . $qtyIndex, $existing)) return null;
            return $existing[$meta->id . "_" . $qtyIndex];
        }
    }

    /**
     * Iterate metadata for item.
     * @param bool $onlyCustomer
     * @return string|null
     */
    public function iterateMeta(bool $onlyCustomer = false): ?string
    {
        if (!$this->item->meta()->count()) return null;
        $data = null;
        foreach ($this->item->meta as $meta)
        {
            if (!$meta->customer_viewable && $onlyCustomer) continue;
            if ($meta->per_qty)
            {
                foreach (range(1, $this->qty) as $idx)
                {
                    $ans = $this->getMetaFor($meta, $idx);
                    if (!$ans) continue;
                    $data .= "<small><b>$meta->item</b>: " . $ans . "</small><br/>";
                }
            }
            else
            {
                if (!$this->getMetaFor($meta)) continue;
                $data .= "<small><b>$meta->item</b>: " . $this->getMetaFor($meta) . "</small><br/>";
            }
        }
        return $data;
    }

    /**
     * This method will get the increase or decrease price difference
     * in percentage based on the catalog pricing.
     * @return int
     */
    public function getDifferenceFromCatalog(): int
    {
        $catalogPrice = $this->getCatalogPrice();
        $diff = $this->price / $catalogPrice;
        return (int) (100 - round($diff * 100));
    }

    /**
     * Get catalog price based on the settings of either MSRP
     * or base pricing.
     * @return int
     */
    public function getCatalogPrice(): int
    {
        if (setting('quotes.showDiscount') == 'None') return 0;
        $catalogPrice = 0;
        if (setting('quotes.showDiscount') == 'Base')
        {
            $catalogPrice = $this->item->type == 'services' ? $this->item->mrc : $this->item->nrc;
        }
        elseif (setting('quotes.showDiscount') == 'MSRP')
        {
            $catalogPrice = $this->item->msrp;
        }
        if ($catalogPrice <= 0) return 0;
        return $catalogPrice;
    }

    /**
     * Update Metadata for an Item
     * @param int         $int
     * @param string|null $val
     * @param int|null    $qtyIdx
     * @return void
     */
    public function updateMeta(int $int, ?string $val = null, ?int $qtyIdx = null): void
    {
        $meta = $this->meta ?: [];
        if ($qtyIdx == null)
        {
            $meta[$int] = $val;
        }
        else
        {
            $meta[$int . "_" . $qtyIdx] = $val;
        }
        $this->update(['meta' => $meta]);
    }


}
