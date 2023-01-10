<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $id
 */
class Addon extends Model
{
    protected $guarded = ['id'];

    /**
     * Addons have many options.
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(AddonOption::class);
    }

    /**
     * An addon belongs to a bill item.
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(BillItem::class, 'bill_item_id');
    }

    /**
     * Return a selectable array of options for this addon.
     * @return array
     */
    public function selectable(): array
    {
        $opts = [];
        $opts[] = '-- Select Option --';
        foreach ($this->options()->orderBy('price')->get() as $option)
        {
            $opts[$option->id] = sprintf("%s (%s$%s)", $option->name, $option->price > -1 ? "+" : "-",
                number_format($option->price, 2));
        }
        return $opts;
    }

    /**
     * Take a quote item and find out what we have selected.
     * @param QuoteItem $item
     * @param string    $type
     * @return string|null
     */
    public function getSelected(QuoteItem $item, string $type = 'id'): ?string
    {
        // Get all addons for this item.
        $addon = QuoteItemAddon::where('quote_item_id', $item->id)->where('addon_id', $this->id)->first();
        if (!$addon) return null;
        return match ($type)
        {
            'name' => $addon->name,
            'price' => number_format($addon->price,2),
            'qty' => $addon->qty,
            default => $addon->addon_option_id
        };
    }

    /**
     * Take a service item and find out what we have selected.
     * @param AccountItem $item
     * @param string      $type
     * @return string|null
     */
    public function getServiceSelected(AccountItem $item, string $type = 'id'): ?string
    {
        // Get all addons for this item.
        $addon = AccountAddon::where('account_id', $item->account->id)->where('addon_id', $this->id)->first();
        if (!$addon) return null;
        return match ($type)
        {
            'name' => $addon->name,
            'price' => number_format($addon->price,2),
            'qty' => $addon->qty,
            default => $addon->addon_option_id,
        };
    }


}
