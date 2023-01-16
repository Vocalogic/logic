<?php

namespace App\Http\Livewire\Shop;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class CartIconComponent extends Component
{
    public array $items = [];
    public int   $count = 0;
    public float $total = 0.00;
    public bool  $mini  = true;



    public $listeners = ['cartUpdated'];

    /**
     * Show cart status
     * @return void
     */
    public function mount(): void
    {
        $this->cartUpdated();
    }

    /**
     * Show cart icon
     * @return View
     */
    public function render(): View
    {
        if ($this->mini)
        {
            return view('shop.cart_icon');
        }
        else
        {
            return view('shop.sidecart');
        }
    }


    /**
     * The session data for the cart was updated, we should
     * render the new items and cart data in the header here.
     * @return void
     */
    public function cartUpdated(): void
    {
        $cart = cart();
        $this->count = count($cart->items);
        $this->items = $cart->items;
        $this->total = $cart->total;
    }


    /**
     * Remove item from cart
     * @param string $uid
     * @return void
     */
    public function removeItem(string $uid)
    {
        $cart = cart();
        $cart->removeItem($uid);
        $this->emit('cartUpdated');
    }


    /**
     * Apply addon description text
     * @param string $uid
     * @return string
     */
    public function exportAddonText(string $uid): string
    {
        $cart = cart();
        $item = $cart->getItem($uid);
        $data = [];
        if (isset($item->appliedAddons))
        {

            foreach ($item->appliedAddons as $addon)
            {
                $data[] = $addon->textPrice;
            }
        }
        return "<br/>". implode("<br/>", $data);
    }
}
