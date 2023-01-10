<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Checkout from a quote.
     * @param string $slug
     * @param string $qslug
     * @return View
     */
    public function quoteCheckout(string $slug, string $qslug) : View
    {

        $lead = Lead::where('hash', $slug)->first();
        if (!$lead) abort(404);
        if (!$lead->active) abort(404);
        $quote = $lead->quotes()->where('hash', $qslug)->first();
        seo()->title("Checkout from Quote #$quote->id");
        if (!$quote) abort(404);
        if ($quote->archived) abort(404);
        return view('shop.checkout.index', ['quote' => $quote]);
    }

    /**
     * Guest Checkout Mode
     * @return View
     */
    public function checkout(): View
    {
        // Guest Checkout
        seo()->title("Checkout");
        return view('shop.checkout.index', ['quote' => null]);
    }

}
