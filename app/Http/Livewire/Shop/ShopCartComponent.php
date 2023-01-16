<?php

namespace App\Http\Livewire\Shop;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Operations\Shop\ShopOperation;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ShopCartComponent extends Component
{
    public Quote $quote;

    /**
     * Assign our cart.
     * @return void
     */
    public function mount(): void
    {
        ShopOperation::setWorkingQuote($this->quote);
        cart(); // Apply via pack process
    }

    /**
     * Increase Quantity
     * @param QuoteItem $item
     * @return void
     */
    public function increaseItem(QuoteItem $item) : void
    {
        $item =  $this->quote->items()->where('id', $item->id)->first();
        $item->update(['qty' => $item->qty + 1]);
    }

    /**
     * Decrease Item
     * @param QuoteItem $item
     * @return void
     */
    public function decreaseItem(QuoteItem $item) : void
    {
        $item =  $this->quote->items()->where('id', $item->id)->first();
        $new = $item->qty - 1;
        if ($new < 1) $new = 1;
        $item->update(['qty' => $new]);
    }

    /**
     * Remove an item from the quote.
     * @param QuoteItem $item
     * @return void
     */
    public function removeItem(QuoteItem $item) : void
    {
        $item->delete();
    }

    /**
     * Render View
     * @return View
     */
    public function render(): View
    {
        return view('shop.presales.quote.component');
    }

}
