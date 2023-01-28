<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $invoice
 * @property mixed $item
 * @property mixed $price
 */
class InvoiceItem extends Model
{
    protected $guarded = ['id'];

    /**
     * An item belongs to an invoice
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * An invoice item can belong to a bill item.
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(BillItem::class, 'bill_item_id');
    }

    /**
     * Take the item's price and compare it with another bill item
     * and return the percentage of increase or decrease along with
     * a tooltip that says what the average price sold for this customer
     * has been.
     * @return string|null
     */
    public function getVariationDetailAttribute(): ?string
    {
        if (!$this->item) return null; // We aren't comparing raw data, just anything with a billitem.
        $total = 0;
        $count = 0;
        foreach ($this->invoice->account->invoices as $oldInvoice)
        {
            if ($oldInvoice->id == $this->invoice->id) continue; // visually cleaner this way.
            foreach ($oldInvoice->items as $item)
            {
                if ($item->item && $item->item->id == $this->item->id)
                {
                    $total += $item->price;
                    $count++;
                }
            }
        }
        if ($count == 0) return null; // First time billing this item - no info needed.
        $average = $total / $count;
        if ($average == 0) return null;  // $0 average - no info.
        $less = $this->price < $average; // Is our current price lower than the average?
        $text = sprintf("Average Price: $%s", moneyFormat($average));
        $icon = $less ? "chevron-down" : "chevron-up"; // Set visual icon
        $color = $less ? "warning" : "success";        // Set color
        $pm = $less ? "-" : "+";                       // Set plus/minus indicator
        if ($less)
        {
            $perc = 100 - round(($this->price / $average) * 100);
        }
        else
        {
            $perc = round(($this->price / $average) * 100) - 100;
        }
        if ($perc == 0) return null;
        return "<span class='small' data-bs-toggle='tooltip' data-bs-placement='top' title='$text'>
            <i class='fa fa-$icon text-$color'>{$pm}{$perc}%</span>";
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
        if (!$this->item) return 0;  // Can be a manual product here. No catalog comparison
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


}
