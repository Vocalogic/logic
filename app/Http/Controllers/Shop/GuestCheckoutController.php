<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class GuestCheckoutController extends Controller
{
    /**
     * Show checkout component
     * @return View
     */
    public function checkout(): View
    {
        seo()->title("Review your Cart");
        return view('shop.checkout.index', ['quote' => null]);
    }

}
