<?php

namespace App\Models;

use App\Traits\HasLogTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $invoice
 * @property mixed $item
 * @property mixed $price
 * @property mixed $code
 * @property mixed $name
 * @property mixed $bill_item_id
 * @property mixed $globalDifference
 */
class InvoiceItem extends Model
{
    use HasLogTrait;
    protected $guarded = ['id'];

    public array $tracked = [
        'code'        => "Item Code",
        'name'        => "Item Name",
        'description' => "Item Description",
        'qty'         => "Quantity",
        'price'       => "Price|money"
    ];

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
     * Get the percentage difference and average cost.
     * @return int[]
     */
    public function getGlobalDifferenceAttribute(): array
    {
        $count = 0; // We want to include qty with this too as it is important.
        $totalCost = 0;
        foreach (self::where('bill_item_id', $this->bill_item_id)->get() as $item)
        {
            $count += $item->qty;
            $totalCost += (int)bcmul($item->qty * $item->price, 1);
        }
        if ($count == 0) return ['perc' => 0, 'average' => 0]; // Don't divide by 0. (Not sure how we would but..)
        $average = (int)bcmul($totalCost / $count, 1);
        if ($average == 0) return ['perc' => 0, 'average' => 0]; // Don't divide by zero again.
        $diff = (($this->price - $average) / $average) * 100;
        return ['perc' => (int)bcmul($diff, 1), 'average' => $average];
    }

    /**
     * Take the invoice item's price and compare it with another bill item
     * and return the percentage of increase or decrease along with
     * a tooltip that says what the average price sold for this customer
     * has been.
     * @return string|null
     */
    public function getVariationDetailAttribute(): ?string
    {
        if (!$this->item) return null; // We aren't comparing raw data, just anything with a billitem.
        $diff = $this->globalDifference;
        $diffPerc = $diff['perc'];
        $average = $diff['average'];
        $less = $diffPerc < 0;
        $text = sprintf("Average Price: $%s", moneyFormat($average));
        $icon = $less ? "chevron-down" : "chevron-up"; // Set visual icon
        $color = $less ? "warning" : "success";        // Set color
        if ($diffPerc == 0) return null;
        return "<span class='small fs-7 text-$color' data-bs-toggle='tooltip' data-bs-placement='top' title='$text'>
            <i class='fa fa-$icon text-$color'></i>{$diffPerc}%</span>";
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
        return (int)(100 - round($diff * 100));
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
