<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\ActivityType;
use App\Enums\Core\BillItemType;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AddonOption;
use App\Models\BillItem;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\QuoteItemAddon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class QuoteController extends Controller
{

    /**
     * Show all active quotes.
     * @return View
     */
    public function index(): View
    {
        // Showing all quotes
        $quotes = Quote::where('archived', false)->get();
        return view('admin.quotes.index', ['quotes' => $quotes]);
    }

    /**
     * Show Quote
     * @param Quote $quote
     * @return View
     */
    public function show(Quote $quote): View
    {
        if (!$quote->expires_on)
        {
            $quote->update(['expires_on' => now()->addDays((int)setting('quotes.length'))]);
            $quote->refresh();
        }
        if ($quote->lead)
        {
            $crumbs = [
                '/admin/leads'                            => "Leads",
                "/admin/leads/{$quote->lead->id}"         => $quote->lead->company,
                "/admin/leads/{$quote->lead->id}/quotes/" => "Quotes",
                "#$quote->id"
            ];
        }
        else
        {
            $crumbs = [
                "/admin/accounts"                               => "Accounts",
                "/admin/accounts/{$quote->account->id}"         => $quote->account->name,
                "/admin/accounts/{$quote->account->id}/quotes/" => "Quotes",
                "#$quote->id"
            ];
        }
        return view('admin.quotes.show', ['quote' => $quote, 'crumbs' => $crumbs]);
    }

    /**
     * Global Quote Creator Modal send in account_id or lead_id
     * depending on context.
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        if ($request->lead_id)
        {
            $obj = Lead::find($request->lead_id);
        }
        else
        {
            $obj = Account::find($request->account_id);
        }
        return view('admin.quotes.create', ['obj' => $obj]);
    }

    /**
     * Create a new Quote from either a lead or account.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $obj = $request->lead_id ? Lead::find($request->lead_id) : Account::find($request->account_id);
        $preferred = $obj->quotes()->count() > 0 ? 0 : 1;
        $quote = (new Quote)->create([
            'name'       => $request->name,
            'lead_id'    => ($obj instanceof Lead) ? $obj->id : 0,
            'account_id' => ($obj instanceof Account) ? $obj->id : 0,
            'agent_id'   => user()->id,
            'hash'       => "QO-" . uniqid(),
            'preferred'  => $preferred,
            'net_terms'  => $request->net_terms ?: setting('invoices.net'),
            'expires_on' => $request->expires_on ?: now()->addDays((int)setting('quotes.length'))
        ]);
        if ($obj instanceof Lead)
        {
            sysact(ActivityType::LeadQuote, $quote->id,
                "started <a href='/admin/quotes/$quote->id'>Quote #{$quote->id}</a> for ");
        }
        else
        {
            sysact(ActivityType::AccountQuote, $quote->id,
                "started <a href='/admin/quotes/$quote->id'>Quote #{$quote->id}</a> for ");
        }
        return redirect()->to("/admin/quotes/$quote->id")->with('message', "Quote #$quote->id created.");
    }

    /**
     * The index from inside the context of a lead.
     * @param Lead $lead
     * @return View
     */
    public function leadIndex(Lead $lead): View
    {
        return view('admin.quotes.index_lead')->with('lead', $lead);
    }

    /**
     * Add an item to a quote.
     * @param Quote    $quote
     * @param BillItem $item
     * @return RedirectResponse
     * @throws LogicException
     */
    public function addItem(Quote $quote, BillItem $item): RedirectResponse
    {
        if ($quote->status == 'Executed')
        {
            throw new LogicException("Quote has already been executed. Unable to add item.");
        }
        $service = $item->type == BillItemType::SERVICE->value;
        $preferredPrice = 0;
        if ($quote->account)
        {
            $preferredPrice = $quote->account->getPreferredPricing($item);
        }
        if (!$preferredPrice)
        {
            $price = $service ? $item->mrc : $item->nrc;
        }
        else $price = $preferredPrice;

        $qitem = (new QuoteItem)->create([
            'quote_id'        => $quote->id,
            'item_id'         => $item->id,
            'price'           => $price,
            'description'     => $item->description,
            'qty'             => 1,
            'allowed_qty'     => $item->allowed_qty,
            'allowed_type'    => $item->allowed_type,
            'allowed_overage' => $item->allowed_overage,
            'frequency'       => $item->type == 'services' ? $item->frequency : null,
        ]);
        $qitem->update(['ord' => $qitem->setNewOrd()]);
        $quote->reord();
        $quote->calculateTax();
        sbus()->emitQuoteUpdated($quote);
        return redirect()->back()->with('message', $item->name . " added to Quote #$quote->id");
    }

    /**
     * Delete an item
     * @param Quote     $quote
     * @param QuoteItem $item
     * @return string[]
     * @throws LogicException
     */
    public function delItem(Quote $quote, QuoteItem $item): array
    {
        if ($quote->status == 'Executed')
        {
            throw new LogicException("Quote has already been executed. Unable to remove item.");
        }
        session()->flash('message', $item->item->name . " removed from quote.");
        $item->delete();
        $quote->reord();
        $quote->calculateTax();
        sbus()->emitQuoteUpdated($quote);
        return ['callback' => "reload"];
    }

    /**
     * Show edit item modal.
     * @param Quote     $quote
     * @param QuoteItem $item
     * @return View
     */
    public function editItem(Quote $quote, QuoteItem $item): View
    {
        return view('admin.quotes.edit_modal')->with('quote', $quote)->with('item', $item);
    }

    /**
     * Update quote item
     * @param Quote     $quote
     * @param QuoteItem $item
     * @param Request   $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function updateItem(Quote $quote, QuoteItem $item, Request $request): RedirectResponse
    {
        if ($quote->status == 'Executed')
        {
            throw new LogicException("Quote has already been executed. Unable to edit item.");
        }
        if (!$request->description)
        {
            $request->merge(['description' => $item->item->description]);
        }
        $item->update([
            'price'           => convertMoney($request->price),
            'description'     => $request->description,
            'qty'             => $request->qty,
            'allowed_qty'     => $request->allowed_qty,
            'allowed_type'    => $request->allowed_type,
            'allowed_overage' => $request->allowed_overage,
            'frequency'       => $request->frequency,
            'payments'        => $request->payments,
            'finance_charge'  => $request->finance_charge,
            'notes'           => $request->notes
        ]);
        $quote->calculateTax();
        sbus()->emitQuoteUpdated($quote);
        return redirect()->back()->with('message', $item->item->name . " updated.");
    }

    /**
     * Update a quote based on a lead.
     * @param Quote   $quote
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function update(Quote $quote, Request $request): RedirectResponse
    {
        if ($quote->status == 'Executed')
        {
            throw new LogicException("Quote has already been executed. Unable to update quote settings.");
        }
        $quote->update([
            'name'       => $request->name,
            'preferred'  => $request->preferred,
            'notes'      => $request->notes,
            'term'       => $request->term,
            'net_terms'  => $request->net_terms,
            'coterm_id'  => $request->coterm_id,
            'expires_on' => $request->expires_on
        ]);
        if ($request->preferred && $quote->lead)
        {
            $quote->lead->quotes()->where('id', '!=', $quote->id)->update(['preferred' => false]);
        }
        return redirect()->back()->with('message', "Quote settings updated.");
    }

    /**
     * Download Quote
     * @param Quote $quote
     * @return mixed
     */
    public function download(Quote $quote)
    {
        return $quote->simplePDF();
    }

    /**
     * Download Contract
     * @param Quote $quote
     * @return mixed
     */
    public function msa(Quote $quote)
    {
        if (!$quote->activated_on) abort(404);
        return $quote->simpleMSA();
    }

    /**
     * Send Quote to Customer
     * @param Quote $quote
     * @return string[]
     */
    public function send(Quote $quote): array
    {
        $quote->send();
        session()->flash("message", "Quote #{$quote->id} sent successfully.");
        return ['callback' => 'reload'];
    }

    /**
     * Remove a quote
     * @param Quote   $quote
     * @param Request $request
     * @return RedirectResponse|array
     * @throws LogicException
     */
    public function destroy(Quote $quote, Request $request): RedirectResponse|array
    {
        if (!$request->reason) throw new LogicException("You must enter a reason for the decline.");
        $quote->update(['declined_reason' => $request->reason]);
        sysact(ActivityType::AccountQuote, $quote->id, "declined Quote #$quote->id ($request->reason)");
        $quote->delete();
        if ($request->ajax())
        {
            return ['callback' => "reload"];
        }
        return redirect()->to("/admin/quotes")->with('message', "Quote #$quote->id Declined");
    }

    /**
     * Toggle if a quote should be visible inside the customer's discovery page.
     * @param Quote $quote
     * @return RedirectResponse
     */
    public function togglePresentable(Quote $quote): RedirectResponse
    {
        $quote->update(['presentable' => !$quote->presentable]);
        return redirect()->back();
    }

    /**
     * Import for Coterming
     * @param Quote $quote
     * @param int   $id
     * @return RedirectResponse
     */
    public function import(Quote $quote, int $id): RedirectResponse
    {
        $src = Quote::find($id);
        foreach ($src->items as $item)
        {
            if ($item->item && $item->item->type == 'services')
            {
                $quote->items()->create([
                    'item_id' => $item->item_id,
                    'price'   => $item->price,
                    'qty'     => $item->qty,
                    'notes'   => $item->notes
                ]);
            }
        }
        return redirect()->to("/admin/accounts/{$quote->account->id}?active=quotes&quote=$quote->id");
    }


    /**
     * Execute coterm contract
     * @param Quote $quote
     * @return array
     */
    public function executeCoterm(Quote $quote): array
    {
        $quote->executeCoterm();
        return ['callback' => "redirect:/admin/accounts/{$quote->account->id}?active=services"];
    }

    /**
     * Copy a quote into quote provided.
     * @param Quote   $quote
     * @param Request $request
     * @return RedirectResponse
     */
    public function copy(Quote $quote, Request $request): RedirectResponse
    {
        if (!$request->quote_id) return redirect()->back()->with('error', 'You must select a quote to copy.');
        $q = Quote::find($request->quote_id);
        if ($request->type == 'recurring')
        {
            foreach ($q->services as $item)
            {
                $new = $item->replicate();
                $quote->items()->save($new);
            }
        }
        else
        {
            foreach ($q->products as $item)
            {
                $new = $item->replicate();
                $quote->items()->save($new);
            }
        }
        return redirect()->back();
    }

    /**
     * Show addon editor for product or service.
     * @param Quote     $quote
     * @param QuoteItem $item
     * @return View
     */
    public function addons(Quote $quote, QuoteItem $item): View
    {
        return view('admin.quotes.addons')->with(['quote' => $quote, 'item' => $item]);
    }

    /**
     * Save addons for a particular quote item.
     * @param Quote     $quote
     * @param QuoteItem $item
     * @param Request   $request
     * @return RedirectResponse
     */
    public function saveAddons(Quote $quote, QuoteItem $item, Request $request): RedirectResponse
    {
        foreach ($request->all() as $key => $val)
        {
            if (preg_match("/add\_/i", $key))
            {
                $x = explode("add_", $key);
                $key = $x[1];
                // $key is the addon id. val is our option we selected.
                $q = QuoteItemAddon::where('addon_id', $key)->where('quote_item_id', $item->id)->first();
                $oitem = AddonOption::find($val);
                if (!$oitem) continue;
                $price = $oitem->price ?: 0;
                if (!$q)
                {
                    QuoteItemAddon::create([
                        'addon_id'        => $key,
                        'quote_item_id'   => $item->id,
                        'addon_option_id' => $val,
                        'price'           => $request->get("price_$key") ? convertMoney($request->get("price_$key")) : $price,
                        'qty'             => $request->get("qty_$key") ?: 1,
                        'name'            => $oitem->name
                    ]);
                }
                else
                {
                    $q->update([
                        'addon_id'        => $key,
                        'quote_item_id'   => $item->id,
                        'addon_option_id' => $val,
                        'price'           => $request->get("price_$key") ? convertMoney($request->get("price_$key")) : $price,
                        'qty'             => $request->get("qty_$key") ?: 1,
                        'name'            => $oitem->name
                    ]);
                }
            } // if match on add_
        }
        return redirect()->back()->with('message', 'Addons Applied Successfully');
    }

    /**
     * Move a product or service up or down the quote
     * @param Quote     $quote
     * @param QuoteItem $item
     * @param string    $direction
     * @return RedirectResponse
     */
    public function move(Quote $quote, QuoteItem $item, string $direction): RedirectResponse
    {
        if ($direction == 'up')
        {
            $item->moveUp();
        }
        else
        {
            $item->moveDown();
        }
        return redirect()->back();
    }

    /**
     * Show metadata editor
     * @param Quote     $quote
     * @param QuoteItem $item
     * @return View
     */
    public function showMeta(Quote $quote, QuoteItem $item): View
    {
        return view('admin.quotes.meta', ['quote' => $quote, 'item' => $item]);
    }

    /**
     * Apply all metadata
     * @param Quote     $quote
     * @param QuoteItem $item
     * @param Request   $request
     * @return RedirectResponse
     */
    public function saveMeta(Quote $quote, QuoteItem $item, Request $request): RedirectResponse
    {
        foreach ($request->all() as $key => $val)
        {
            if (preg_match("/a_/i", $key))
            {
                $x = explode("_", $key); // key could be a_3 or a_3_1
                if (!isset($x[2]))
                {
                    $item->updateMeta((int)$x[1], $val);
                }
                else
                {
                    $item->updateMeta((int)$x[1], $val, (int)$x[2]);
                }
            }
        }
        return redirect()->back()->with('message', "Requirements saved successfully.");
    }

    /**
     * Approve quote for sending.
     * @param Quote $quote
     * @return string[]
     */
    public function approve(Quote $quote): array
    {
        $quote->update([
            'approved'    => true,
            'approved_by' => user()->id,
            'approved_on' => now()
        ]);
        return ['callback' => 'reload'];
    }
}
