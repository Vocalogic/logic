<?php

namespace App\Http\Controllers\Sales;

use App\Enums\Core\ActivityType;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class SalesQuoteController extends Controller
{
    /**
     * Create quote from a cart.
     * @param Lead $lead
     * @return RedirectResponse
     */
    public function create(Lead $lead): RedirectResponse
    {
        if (!$lead->active) abort(404);
        if ($lead->agent_id != user()->id) abort(401);
        $cart = cart();
        $quote = $cart->createQuoteFromCart();
        $quote->lead_id = $lead->id;
        $quote->name = "Quote for " . $lead->company;
        $quote->save();
        $quote->refresh();
        $cart->removeAll();
        sysact(ActivityType::LeadQuote, $quote->id, "created <a href='/admin/leads/{$lead->id}/quotes/{$quote->id}'>Quote #$quote->id</a> for {$lead->company}");
        if ($lead->quotes()->where('preferred', true)->count() == 0)
        {
            $quote->update(['preferred' => true]);
        }
        return redirect()->to("/sales/leads/$lead->id/quotes/$quote->id");
    }

    /**
     * Show Quote Editor for Agent
     * @param Lead  $lead
     * @param Quote $quote
     * @return View
     */
    public function show(Lead $lead, Quote $quote): View
    {
        if (!$lead->active) abort(404);
        if ($lead->agent_id != user()->id) abort(401);
        if ($quote->archived) abort(404);
        if ($quote->lead_id != $lead->id) abort(401);
        return view('shop.sales.leads.quotes.show', ['lead' => $lead, 'quote' => $quote]);
    }

    /**
     * Update a Quote
     * @param Lead    $lead
     * @param Quote   $quote
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Lead $lead, Quote $quote, Request $request): RedirectResponse
    {
        foreach($request->all() as $key => $val)
        {
            if (preg_match("/q_/i", $key))
            {
                $x = explode("q_", $key);
                QuoteItem::find($x[1])->update(['qty' => $val]);
            }
            if (preg_match("/p_/i", $key))
            {
                $x = explode("p_", $key);
                $qi = QuoteItem::find($x[1]);
                $priceReq = convertMoney($val);
                if ($qi->item->min_price && ($priceReq < $qi->item->min_price)) $priceReq = $qi->item->min_price;
                if ($qi->item->max_price && ($priceReq > $qi->item->max_price)) $priceReq = $qi->item->max_price;
                $qi->update(['price' => $priceReq]);
            }
        }
        $quote->update(['term' => $request->term, 'name' => $request->name]);
        return redirect()->back();
    }

    /**
     * Remove an item from a quote.
     * @param Lead      $lead
     * @param Quote     $quote
     * @param QuoteItem $item
     * @return string[]
     */
    public function removeItem(Lead $lead, Quote $quote, QuoteItem $item): array
    {
        $item->delete();
        return ['callback' => 'reload'];
    }

    /**
     * Download Quote
     * @param Lead  $lead
     * @param Quote $quote
     * @return mixed
     */
    public function download(Lead $lead, Quote $quote) : mixed
    {
        return $quote->simplePDF();
    }

    /**
     * Send quote to customer.
     * @param Lead  $lead
     * @param Quote $quote
     * @return RedirectResponse
     */
    public function send(Lead $lead, Quote $quote) : RedirectResponse
    {
        $quote->send();
        return redirect()->back()->with('message', "Quote Sent to Customer for Review");
    }

    /**
     * Apply cart items to quote.
     * @param Lead  $lead
     * @param Quote $quote
     * @return RedirectResponse
     */
    public function applyCart(Lead $lead, Quote $quote): RedirectResponse
    {
        $cart = cart();
        $cart->applyCartItemsToQuote($quote);
        $cart->removeAll();
        return redirect()->back()->with('message', "Cart Items applied to Cart");
    }

    /**
     * Toggle Presentable for Quote
     * @param Lead  $lead
     * @param Quote $quote
     * @return RedirectResponse
     */
    public function presentable(Lead $lead, Quote $quote): RedirectResponse
    {
        $quote->update(['presentable' => !$quote->presentable]);
        return redirect()->back();
    }

    /**
     * Decline a quote modal
     * @param Lead  $lead
     * @param Quote $quote
     * @return View
     */
    public function declineModal(Lead $lead, Quote $quote): View
    {
        return view('shop.sales.leads.quotes.decline', ['lead' => $lead, 'quote' => $quote]);
    }

    /**
     * Decline a quote
     * @param Lead    $lead
     * @param Quote   $quote
     * @param Request $request
     * @return RedirectResponse
     */
    public function decline(Lead $lead, Quote $quote, Request $request): RedirectResponse
    {
        $request->validate(['reason' => "required"]);
        $quote->update(['archived' => 1, 'declined_reason' => $request->reason]);
        sysact(ActivityType::AccountQuote, $quote->id, "declined Quote #$quote->id ($request->reason)");
        return redirect()->back()->with('message', "Quote Archived");
    }
}
