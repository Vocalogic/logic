<?php

namespace App\Operations\Shop;

use App\Enums\Core\CommKey;
use App\Models\BillItem;
use App\Models\Quote;

/**
 * ShopBus will handle all of the communications and requests from admin
 * allowing a guided cart experience.
 */
class ShopBus
{
    /**
     * Localized Cart Variable (after unpacking cache)
     * @var array
     */
    public array $carts = [];

    /**
     * Length of time before we assume the client disconnected/navigated away.
     * @var int
     */
    static public int $lifetimeMinutes = 3;

    /**
     * Sync our master cached cart array to track
     * all carts active in the system.
     */
    public function __construct()
    {
        $this->unpack();
    }


    /**
     * Get a cart tracking session
     * @param string $uid
     * @return object|null
     */
    public function get(string $uid): ?object
    {
        if (!array_key_exists($uid, $this->carts)) return null; // Cart not found.. Stray bullet
        return $this->carts[$uid];
    }

    /**
     * Receives a cart from an independent session.
     * @param string $uid
     * @param object $cart
     * @return void
     */
    public function receive(string $uid, object $cart): void
    {
        // Cart should be saved as a root object and then the cart inside the cart object
        // We will be storing sessions as the key in the carts array.
        // Per #18 we will not be executing this by default and will require the customer to initiate.
        $bots = ['bot', 'ahref'];
        foreach ($bots as $bot)
        {
            if (preg_match("/$bot/i", app('request')->header('User-Agent'))) return;
        }

        $obj = (object)[
            'id'            => $uid,
            'ip'            => app('request')->ip(),
            'browser'       => app('request')->header('User-Agent'),
            'page'          => app('request')->url(),
            'last_activity' => now(),
            'cart'          => $cart,
            'code'          => mt_rand(1000, 9999),
            'authorized'    => false
        ];
        $this->carts[$uid] = $obj;
        $this->pack();
    }

    /**
     * When user creates activity, like loading a page or something
     * @param string $uid
     * @return void
     */
    public function ping(string $uid): void
    {
        if (!array_key_exists($uid, $this->carts)) return; // Cart not found.. Stray bullet
        $this->carts[$uid]->last_activity = now();
        $this->carts[$uid]->page = app('request')->url();
        $this->pack();
    }


    /**
     * Saves the global cache state.
     * @param bool $force
     * @return void
     */
    private function pack(bool $force = false): void
    {
        if (sizeOf($this->carts) == 0 && !$force)
        {
            return;
        }
        cache([CommKey::GlobalCartCache->value => $this->carts], CommKey::GlobalCartCache->getLifeTime());
    }

    /**
     * Send User to a Url in an active session (to a product, etc.)
     * @param string $uid
     * @param string $url
     * @return void
     */
    public function sendTo(string $uid, string $url): void
    {
        if (!array_key_exists($uid, $this->carts)) return;
        $this->carts[$uid]->command = "sendto|$url";
        $this->ping($uid);
        $this->pack();
    }

    /**
     * Send message to client to reload.
     * @param string $uid
     * @return void
     */
    public function sendReload(string $uid): void
    {
        if (!array_key_exists($uid, $this->carts)) return;
        $this->carts[$uid]->command = "reload";
        $this->ping($uid);
        $this->pack();
    }

    /**
     * Send a command to update the object with newly provided information from admin.
     * @param string $uid
     * @param string $iid
     * @param object $item
     * @return void
     */
    public function updateItem(string $uid, string $iid, object $item): void
    {
        if (!array_key_exists($uid, $this->carts)) return;
        $this->carts[$uid]->command = "updateItem";
        $this->carts[$uid]->itemRef = $iid;
        $this->carts[$uid]->itemData = $item;
        $this->ping($uid);
        $this->pack();
    }

    /**
     * Send a command to refresh the cart
     * @param string $uid
     * @return void
     */
    public function updateCart(string $uid): void
    {
        if (!array_key_exists($uid, $this->carts)) return;
        $this->carts[$uid]->command = "updateCart";
        $this->ping($uid);
        $this->pack();
    }

    /**
     * Add Item to Cart
     * @param string   $uid
     * @param BillItem $item
     * @param int      $qty
     * @return void
     */
    public function addItem(string $uid, BillItem $item, int $qty = 1): void
    {
        if (!array_key_exists($uid, $this->carts)) return;
        $this->carts[$uid]->command = "addItem";
        $this->carts[$uid]->itemRef = $item->id;
        $this->carts[$uid]->itemData = $qty;
        $this->ping($uid);
        $this->pack();
    }

    /**
     * Send remove command
     * @param string $uid
     * @param string $iid
     * @return void
     */
    public function removeItem(string $uid, string $iid): void
    {
        if (!array_key_exists($uid, $this->carts)) return;
        $this->carts[$uid]->command = "removeItem";
        $this->carts[$uid]->itemRef = $iid;
        $this->ping($uid);
        $this->pack();
    }

    /**
     * Show Toast to Customer
     * @param string $uid
     * @param string $message
     * @return void
     */
    public function sendMessage(string $uid, string $message): void
    {
        if (!array_key_exists($uid, $this->carts)) return; // Cart not found.. Stray bullet
        $this->carts[$uid]->message = $message;
        $this->carts[$uid]->command = "sendMessage";
        $this->ping($uid);
        $this->pack();
    }

    /**
     * Our Shop Assistant communicator executed the command presented to the session.
     * @param string $uid
     * @return void
     */
    public function setExecuted(string $uid): void
    {
        if (!array_key_exists($uid, $this->carts)) return; // Cart not found.. Stray bullet
        $this->carts[$uid]->executed = true;
        $this->ping($uid);
        $this->pack();
    }

    /**
     * Clear Personal defined message, command, modal, etc.
     * @param string $uid
     * @return void
     */
    public function clear(string $uid): void
    {
        if (!array_key_exists($uid, $this->carts)) return; // Cart not found.. Stray bullet
        $cart = $this->carts[$uid];
        $clearable = ['message', 'command', 'modal', 'itemRef', 'itemData', 'executed'];
        foreach ($clearable as $clear)
        {
            if (isset($cart->{$clear})) unset($cart->{$clear});
        }
        $this->pack();
    }

    /**
     * Show Modal to Customer
     * @param string $uid
     * @param string $message
     * @return void
     */
    public function sendModal(string $uid, string $message): void
    {
        if (!array_key_exists($uid, $this->carts)) return; // Cart not found.. Stray bullet
        $this->carts[$uid]->modal = $message;
        $this->pack();
    }


    /**
     * Unpack our object and set our working array.
     * @return void
     */
    private function unpack(): void
    {
        $carts = cache(CommKey::GlobalCartCache->value);
        if (!$carts || !is_array($carts))
        {
            $carts = [];
        }
        $this->carts = $carts;
    }

    /**
     * Attempt to get a Session by an actively viewed quote.
     * @param Quote $quote
     * @return object|null
     */
    public function findSessionByQuote(Quote $quote): ?object
    {
        foreach ($this->carts as $cart)
        {
            if (isset($cart->cart->get('quote')->id) && $cart->cart->get('quote')->id == $quote->id)
            {
                return $cart;
            }
        }
        return null;
    }

    /**
     * We updated a quote, lets tell the shop bus.
     * @param Quote $quote
     * @return void
     */
    public function emitQuoteUpdated(Quote $quote): void
    {
        foreach ($this->carts as $cart)
        {
            if (isset($cart->cart->get('quote')->id) && $cart->cart->get('quote')->id == $quote->id)
            {
                // We found our cart session for the quote.
                $this->updateCart($cart->id);
                $this->ping($cart->id);
            }
        }
    }

    /**
     * User clicked on the help icon which allows the cart session
     * to shop up in admin.
     * @param string $uid
     * @return void
     */
    public function authorize(string $uid) : void
    {
        if (!array_key_exists($uid, $this->carts)) return;
        $this->carts[$uid]->authorized = true;
        $this->pack();
    }

    /**
     * Clean up Expired Sessions
     * @return void
     */
    static public function cleanUp(): void
    {
        $bus = sbus();
        foreach ($bus->carts as $idx => $cart)
        {
            if ($cart->last_activity->diffInMinutes() >= self::$lifetimeMinutes)
            {
                unset($bus->carts[$idx]);
            }
        }
        $bus->pack(true);
    }
}
