<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\PackageBuild;
use Illuminate\View\View;

class GuestCartController extends Controller
{
    /**
     * Show guest cart component
     * @return View
     */
    public function showCart(): View
    {
        seo()
            ->title("Review your Cart")
            ->description("Verify your items in your cart and make changes where necessary");

        return view('shop.cart.index');
    }

    /**
     * Show quote component.
     * @return View
     */
    public function quote(): View
    {
        return view('shop.cart.quote.index');
    }

    /**
     * Start Building a Cart
     * @param string $slug
     * @return View
     */
    public function startBuild(string $slug): View
    {
        $build = PackageBuild::where('slug', $slug)->first();
        if (!$build) abort(404);
        return view('shop.package.index', ['build' => $build]);
    }
}
