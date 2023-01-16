<?php

namespace App\Http\Livewire\Admin;

use App\Models\BillItem;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Redirector;

class PricingComponent extends Component
{
    public BillItem $item;
    public ?int     $variance     = 0;
    public ?int     $saleVariance = 0;

    public bool $locked = true;

    public float $sellingPrice     = 0.0;
    public float $minAllowed       = 0.0;
    public float $maxAllowed       = 0.0;
    public float $profitAmount     = 0.00;
    public float $profitPercentage = 0.0;


    public $rules = [
        'item.msrp'      => '',
        'item.ex_capex'  => '',
        'item.ex_opex'   => '',
        'variance'       => '',
        'saleVariance'   => '',
        'item.mrc'       => '',
        'item.nrc'       => '',
        'item.min_price' => '',
        'item.max_price' => ''
    ];

    public function mount(): void
    {
        $this->variance = (int)setting('quotes.desiredPerc');
        $this->saleVariance = (int)setting('quotes.variancePerc');
        $this->calculate();
    }

    /**
     * Unlock Form
     * @return void
     */
    public function unlock(): void
    {
        $this->locked = false;
    }

    /**
     * Render Component
     * @return View
     */
    public function render(): View
    {
        $this->calculate();
        return view('admin.bill_items.pricingComponent');
    }

    /**
     * Apply values to record.
     * @return Redirector
     */
    public function apply(): Redirector
    {
        $this->calculate();
        if ($this->item->type == 'products')
        {
            $this->item->nrc = $this->sellingPrice;
        }
        else
        {
            $this->item->mrc = $this->sellingPrice;
        }
        $this->item->min_price = $this->minAllowed;
        $this->item->max_price = $this->maxAllowed;
        $this->item->save();
        $this->item->refresh();
        return redirect()->to("/admin/category/{$this->item->category->id}/items/{$this->item->id}");
    }

    /**
     * Calculate the pricing based on arguments given.
     * @return void
     */
    private function calculate(): void
    {
        if (setting('quotes.pricingMethod') == 'Cost')
        {
            $this->fromCost();
        }
        else
        {
            $this->fromMSRP();
        }
    }

    /**
     * Calculate recommended values based on cost of item.
     * @return void
     */
    private function fromCost(): void
    {
        $cost = $this->item->type == 'products' ? $this->item->ex_capex : $this->item->ex_opex;
        if (!$cost) return;
        if ($this->variance <= 0) $this->sellingPrice = $this->item->msrp;
        $baseVariance = $cost * ($this->variance / 100);
        $this->sellingPrice = $baseVariance + $cost;

        $variance = $this->sellingPrice * ($this->saleVariance / 100);
        $min = $this->sellingPrice - $variance;
        $max = $this->sellingPrice + $variance;
        $this->minAllowed = $min;
        $this->maxAllowed = $max;
        $this->determineProfit();
    }

    /**
     * Calculate recommended values based on MSRP
     * @return void
     */
    private function fromMSRP(): void
    {

        if ($this->variance <= 0) $this->sellingPrice = $this->item->msrp;
        $baseVariance = $this->item->msrp * ($this->variance / 100);
        $this->sellingPrice = $this->item->msrp - $baseVariance;
        $variance = $this->sellingPrice * ($this->saleVariance / 100);
        $min = $this->sellingPrice - $variance;
        $max = $this->sellingPrice + $variance;
        $this->minAllowed = $min;
        $this->maxAllowed = $max;
        $this->determineProfit();
    }

    /**
     * Determine Profit
     * @return void
     */
    private function determineProfit(): void
    {
        $cost = $this->item->type == 'products' ? $this->item->ex_capex : $this->item->ex_opex;
        $diff = $this->sellingPrice - $cost;
        $this->profitAmount = $diff;
        $this->profitPercentage = ($diff / $this->sellingPrice) * 100;
    }
}
