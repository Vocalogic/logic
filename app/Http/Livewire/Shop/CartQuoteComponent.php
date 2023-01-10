<?php

namespace App\Http\Livewire\Shop;

use Illuminate\View\View;
use Livewire\Component;

class CartQuoteComponent extends Component
{


    /**
     * Render Quote Component
     * @return View
     */
    public function render(): View
    {
        return view('shop.cart.quote.component');
    }

}
