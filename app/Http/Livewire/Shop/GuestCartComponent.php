<?php

namespace App\Http\Livewire\Shop;

use App\Exceptions\LogicException;
use App\Models\Addon;
use App\Models\BillItem;
use App\Models\Coupon;
use Illuminate\View\View;
use Livewire\Component;

class GuestCartComponent extends Component
{
    public array   $items           = [];
    public array   $services        = [];
    public array   $products        = [];
    public float   $serviceTotal    = 0;
    public float   $productTotal    = 0;
    public float   $total           = 0;
    public bool    $mini            = false;
    public ?Coupon $coupon          = null;

    public string $errorMessage   = '';
    public string $couponField    = '';
    public string $successMessage = '';

    public $listeners = ['cartUpdated'];


    /**
     * Prebuild Cart Items from Session
     * @return void
     */
    public function mount(): void
    {
        $this->reinit();
    }

    /**
     * Repopulate services and calculate totals.
     * @return void
     */
    private function reinit(): void
    {
        $cart = cart();
        $this->services = $cart->services;
        $this->products = $cart->products;
        $this->serviceTotal = $cart->totalMonthly;
        $this->productTotal = $cart->totalOne;
        $this->total = $cart->total;
    }

    /**
     * Listen for a cart update.
     * @return void
     */
    public function cartUpdated(): void
    {
        $this->reinit();
    }

    /**
     * Show Cart Component
     * @return View
     */
    public function render(): View
    {
        $this->reinit();
        if ($this->total == 0)
        {
            return view('shop.cart.empty');
        }

        return view('shop.cart.component');
    }


    /**
     * Increase Quantity
     * @param string $uid
     * @return void
     */
    public function increaseItem(string $uid): void
    {
        $cart = cart();
        try
        {
            $cart->increaseQty($uid);
        } catch (LogicException $e)
        {
            $this->errorMessage = $e->getMessage();
        }
        $this->emit('cartUpdated');
        $this->reinit();
    }

    /**
     * Decrease Item
     * @param string $uid
     * @return void
     */
    public function decreaseItem(string $uid): void
    {
        $cart = cart();
        $cart->decreaseQty($uid);
        $this->emit('cartUpdated');
        $this->reinit();
    }

    /**
     * Remove an item from the quote.
     * @param string $uid
     * @return void
     */
    public function removeItem(string $uid): void
    {
        $cart = cart();
        $cart->removeItem($uid);
        $this->emit('cartUpdated');
        $this->reinit();
    }



    /**
     * Show text for addons (as configured)
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
        return "<br/>" . implode("<br/>", $data);
    }

    /**
     * Attempt to apply the coupon
     * @return void
     */
    public function applyCoupon(): void
    {
        $coupon = Coupon::where('coupon', $this->couponField)->first();
        if (!$coupon)
        {
            $this->errorMessage = "Coupon not found or has expired.";
            return;
        }
        if (!$coupon->canBeApplied())
        {
            $this->errorMessage = "Coupon has either expired or none remaining.";
            return;
        }
        // Apply the coupon
        $cart = cart();
        $cart->applyCoupon($coupon);
        $this->coupon = $coupon;
        $this->errorMessage = '';
        $this->successMessage = $coupon->coupon . " applied at checkout!";

    }

}
