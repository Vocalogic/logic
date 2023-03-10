<?php

namespace App\Http\Livewire\Admin;

use App\Operations\API\Geo\IPGeo;
use App\Operations\Shop\ShopBus;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ShopMonitorComponent extends Component
{
    public array $carts = [];
    public array $ipLocators = [];

    /**
     * Set our current active carts
     * @return void
     * @throws GuzzleException
     */
    public function mount(): void
    {
        $this->loadActivity();
    }

    /**
     * Show widget
     * @return View
     */
    public function render(): View
    {
        return view('admin.dashboard.carts_component');
    }

    /**
     * Update our carts
     * @return void
     */
    public function loadActivity() : void
    {
        ShopBus::cleanUp();
        $carts = cache('cart_list') ?: [];
        // We need to cleanup our cart array.
        $this->carts = [];
        foreach ($carts as $cart)
        {
            if ($cart->authorized) $this->carts[] = $cart;
        }
        $x = new IPGeo();
        foreach ($this->carts as $cart)
        {
            try {
                $ip = $x->get($cart->ip);
                if (isset($ip->status) && $ip->status == 'success')
                {
                    $this->ipLocators[$cart->id] = $ip;
                }

            } catch(Exception)
            {
                info("Failed to get IP. Skipping");
            }
        }
    }

}
