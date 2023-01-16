<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\ActivityType;
use App\Enums\Core\LeadStatus;
use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\BillItem;
use App\Models\Lead;
use App\Models\Quote;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    /**
     * Get Cart Session
     * @param string $uid
     * @return View|RedirectResponse
     */
    public function show(string $uid): View|RedirectResponse
    {
        $cart = sbus()->get($uid);
        if (!$cart) return redirect()->to("/");
        return view('admin.assistant.show', ['cart' => $cart]);
    }

    /**
     * Show appropriate modal for issuing a command
     * @param string $uid
     * @param string $command
     * @return View
     */
    public function prepare(string $uid, string $command): View
    {
        $view = null;
        switch ($command)
        {
            case 'url' :
                $view = 'admin.assistant.url_modal';
                break;
            case 'quote' :
                $view = 'admin.assistant.quote_modal';
                break;
        }
        return view($view, ['uid' => $uid]);
    }

    /**
     * Send Command to Guest Session
     * @param string  $uid
     * @param string  $command
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function sendCommand(string $uid, string $command, Request $request): RedirectResponse
    {
        switch ($command)
        {
            case 'reload' :
                sbus()->sendReload($uid);
                break;
            case 'review' :
                sbus()->sendTo($uid, '/shop/cart');
                break;
            case 'url' :
                if (!$request->product_id && !$request->service_id)
                {
                    throw new LogicException("You must select a product or service.");
                }
                $item = $request->product_id ? BillItem::find($request->product_id) : BillItem::find($request->service_id);
                $destination = sprintf("/shop/%s/%s", $item->category->slug, $item->slug);
                sbus()->sendTo($uid, $destination);
                break;
            case 'quote' :
                // Create a quote and then redirect user to a preparation page.
                if (!$request->contact || !$request->company || !$request->email)
                {
                    throw new LogicException("Contact, Company and Email is required.");
                }
                $quote = $this->createQuote($uid, $request);
                sbus()->sendTo($uid, "/shop/prepared/$quote->hash");
                sysact(ActivityType::LeadQuote, $quote->id, "created a quote from a cart for ");
                break;
            case 'request' :
                $message =
                    "<h3>".user()->name. " has joined your session!</h3>
                    <br/><p><b>".user()->first . "</b> is here to assist you live through the navigation process. Please note that
                these assisted functions will cease to function when you leave the site. <Br/><br/>This is intended to help guide you
                through our products and services without the need to locate items yourself.</p>";
                sbus()->sendMessage($uid, $message);
                break;
        }
        return redirect()->back();
    }

    /**
     * Show Item Editor
     * @param string $uid
     * @param string $id
     * @return View
     */
    public function showItem(string $uid, string $id): View
    {
        $cart = sbus()->get($uid);
        $item = null;
        foreach ($cart->cart->get('items') as $citem)
        {
            if ($citem->uid == $id)
            {
                $item = $citem;
            }
        }
        return view('admin.assistant.item_modal', ['uid' => $uid, 'iid' => $id, 'item' => $item]);
    }

    /**
     * Send a message to sbus to update this item.
     * @param string  $uid
     * @param string  $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateItem(string $uid, string $id, Request $request) : RedirectResponse
    {
        $cart = sbus()->get($uid);
        foreach ($cart->cart->get('items') as $citem)
        {
            if ($citem->uid == $id)
            {
                $item = $citem;
                $item->price = convertMoney($request->price);
                $item->qty = $request->qty;
                $item->notes = $request->notes;
                sbus()->updateItem($uid, $id, $item);
            } // if found
        } // fe citem
        return redirect()->back()->with('message', 'Item Updated');
    } // fn

    /**
     * Remove an item from the cart
     * @param string $uid
     * @param string $id
     * @return array
     */
    public function removeItem(string $uid, string $id): array
    {
        $cart = sbus()->get($uid);
        foreach ($cart->cart->get('items') as $citem)
        {
            if ($citem->uid == $id)
            {
                sbus()->removeItem($uid, $id);
            } // if found
        } // fe citem
        return ['callback' => 'reload'];
    }

    /**
     * Show add modal
     * @param string $uid
     * @param string $type
     * @return View
     */
    public function addModal(string $uid, string $type): View
    {
        return view('admin.assistant.add_modal', ['uid' => $uid, 'type' => $type]);
    }

    /**
     * Add item to cart
     * @param string  $uid
     * @param Request $request
     * @return RedirectResponse
     * @throws LogicException
     */
    public function addItem(string $uid, Request $request): RedirectResponse
    {
        if (!$request->service_id && !$request->product_id)
        {
            throw new LogicException("You must select an item to add.");
        }
        $item = $request->service_id ? BillItem::find($request->service_id) : BillItem::find($request->product_id);
        sbus()->addItem($uid, $item, $request->qty);
        return redirect()->back()->with('message', $item->name . " added to cart.");
    }

    /**
     * Create a quote from an admin
     * @param string  $cid
     * @param Request $request
     * @return Quote
     */
    private function createQuote(string $cid, Request $request) : Quote
    {
        $lead = (new Lead)->create([
            'company'       => $request->company,
            'contact'       => $request->contact,
            'email'         => $request->email,
            'active'        => true,
            'hash'          => uniqid('D-'),
            'agent_id'       => user()->id,
            'lead_type_id'  => 0,
            'guest_created' => 1,
            'status'        => LeadStatus::QuoteSent
        ]);
        $lead->refresh();
        $quote = $lead->quotes()->create([
            'lead_id'    => $lead->id,
            'hash'       => uniqid('QO-'),
            'status'     => 'Created from Cart',
            'archived'   => false,
            'preferred'  => true,
            'sent_on'    => now(),
            'name'       => "Cart Quote for $lead->company",
            'expires_on' => now()->addDays((int)setting('quotes.length'))
        ]);
        $quote->refresh();
        $cart = sbus()->get($cid);

        foreach ($cart->cart->get('items') as $i)
        {
            $item = $quote->items()->create([
                'item_id'     => $i->id,
                'price'       => $i->price,
                'qty'         => $i->qty,
                'description' => $i->description
            ]);
            $item->refresh();
            if (isset($i->appliedAddons) && is_array($i->appliedAddons))
            {
                foreach ($i->appliedAddons as $add)
                {
                    $item->addons()->create([
                        'addon_option_id' => $add->option_id,
                        'name'            => $add->text,
                        'price'           => $add->price,
                        'qty'             => 1,
                        'addon_id'        => $add->addon_id
                    ]);
                }
            }
        }
        $quote->refresh();
        return $quote;
    }
}
