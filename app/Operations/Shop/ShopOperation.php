<?php

namespace App\Operations\Shop;

use App\Enums\Core\ACL;
use App\Enums\Core\ActivityType;
use App\Enums\Core\CommKey;
use App\Exceptions\LogicException;
use App\Models\Account;
use App\Models\Addon;
use App\Models\AddonOption;
use App\Models\BillItem;
use App\Models\Coupon;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * This class will handle all constructions of the ecommerce process, coupons,
 * from a quote, discounts, totals and more. All Livewire components should
 * resort to this global class and not rely on any session data, as it will
 * be parsed, organized and returned in a uniformed manner.
 *
 * This will also handle exection based on the type of cart. If it's a guest cart,
 * a quote cart, or an account cart.
 */
class ShopOperation
{
    /**
     * This will contain the full exportable configuration of our cart to be used by
     * all the livewire components to be used everywhere. The cart, checkout, icon, etc.
     * This will also contain any computed properties needed for totals, count, and more.
     * @var Collection
     */
    public Collection $cart;


    /**
     * If a coupon is assigned, it will be placed here.
     * @var Coupon|null
     */
    public ?Coupon $coupon = null;
    public ?Quote  $quote  = null;

    // --- Internal Counters and Processors Here --- //
    public array   $products       = [];                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      // Contain all Products
    public array   $services       = [];                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              // Contain all Services
    public array   $items          = [];                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              // Contains all Items
    public float   $total          = 0;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               // Cart Grand Total
    public float   $totalDiscounts = 0;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               // Track how many discounts were given.
    public float   $totalMonthly   = 0;
    public float   $totalOne       = 0;
    public ?string $uid            = null;
    /**
     * This is so we don't send a welcome email to an existing account.
     * @var bool
     */
    public bool $isNewAccount = false;
    /**
     * When creating our accounts, etc.. we need the info from the checkout component.
     * @var array
     */
    public array $infoData = [];

    /**
     * Instantiate our tracked fields that are packed/unpacked
     * @var array|string[]
     */
    private array $tracked = [
        'products',
        'services',
        'items',
        'total',
        'totalDiscounts',
        'totalMonthly',
        'totalOne',
        'coupon',
        'quote',
        'uid',
    ];

    /**
     * Initialize The Cart System based on the type of account that has
     * requests this object. We will either unpack the session collection
     * directly and set all required items or build a new one and pack it.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        if (session(CommKey::LocalCartSession->value))
        {
            try
            {
                $this->unpack();
            } catch (Exception $e) // In case of update while someone is using it.
            {
                info("Shop Error: " . $e->getMessage());
                $this->init();
            }
        }
        else
        {
            $this->init();
        }
    }





    // --- Publicly Accessibly Methods for Manipulating the Cart --- //

    /**
     * Add an Item to the Cart and assign it an internal LID for
     * adding and removing. Will avoid removing 2 of the same item. This
     * will add a second copy of an item if is manually added and the qty
     * is now increased/decreased. This will return the newly formed
     * object with proper pricing, etc.
     * @param BillItem $item
     * @param int      $qty
     * @return object
     * @throws LogicException
     */
    public function addItem(BillItem $item, int $qty = 1): object
    {
        $this->checkOnHand($item->id, $qty);
        $item->qty = $qty;
        $item->addonTotal = 0;
        if ($item->reservation_mode)
        {
            $item->price = $item->reservation_price;
        }
        else
        {
            if (auth()->guest())
            {
                $item->price = $item->msrp;
            }
            else
            {
                $item->price = $item->type == 'services' ? $item->mrc : $item->nrc;
            }
        }
        $item->uid = uniqid();
        $item->canUpdateQty = true;
        $item->couponApplied = false;
        $item->discountedAmount = 0;
        $item->priceBeforeContract = $item->price; // Hold this in case we change around the contract options;
        // Add to all items
        $this->items[] = $item;

        $this->pack();
        $this->processCoupon();
        return $item;
    }

    /**
     * Convert a QuoteItem into a BillItem to keep with the same structure
     * @param QuoteItem $item
     * @return object|null
     */
    public function addQuoteItem(QuoteItem $item): ?object
    {
        $i = $item->item;
        if (!$i)
        {
            $item->delete(); // orphaned.
            return null;
        }
        $i->qty = $item->qty;
        $i->addonTotal = 0;
        $i->price = $item->price;
        $i->uid = uniqid();
        $i->canUpdateQty = false;
        $i->couponApplied = true; // Cannot apply a coupon to a quoted item.
        $i->discountedAmount = 0;

        // Add to all items
        $this->items[] = $i;
        if ($item->item->type == 'services')
        {
            $this->services[] = $i;
        }
        else $this->products[] = $i;
        $this->pack();
        return $i;
    }

    /**
     * This will update our cart item with an updated Quote Item
     * @param string    $uid
     * @param QuoteItem $item
     * @return void
     */
    public function updateQuoteItem(string $uid, QuoteItem $item): void
    {
        $cartItem = $this->getItem($uid);
        $cartItem->qty = $item->qty;
        $cartItem->price = $item->price;
        $this->pack();
    }

    /**
     * This will take the quote assigned in the object and iterate a fresh copy
     * and sync up items with the quote. The idea is that the sales agent
     * can modify their cart in realtime.
     * @return void
     */
    public function syncCartByQuote(): void
    {
        if (session(CommKey::LocalQuoteSession->value))
        {
            $this->quote = session(CommKey::LocalQuoteSession->value);
        }
        else
        {
            return;
        }
        $quote = Quote::find($this->quote->id);
        $this->items = [];
        foreach ($quote->items()->orderBy('ord')->get() as $item)
        {
            $this->addQuoteItem($item);
        }
    }


    /**
     * Get Item details by LID
     * @param string $uid
     * @return object|null
     */
    public function getItem(string $uid): ?object
    {
        foreach ($this->items as $item)
        {
            if ($item->uid == $uid) return $item;
        }
        return null;
    }

    /**
     * Attempt to find the first billitem UID based on an actual
     * BillItem. This may need to be an array in the future.
     * @param BillItem $item
     * @param bool     $retry
     * @return string|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUidByItem(BillItem $item, bool $retry = false): ?string
    {
        foreach ($this->items as $cartItem)
        {
            if ($item->id == $cartItem->id)
            {
                if ($cartItem->uid) return $cartItem->uid;
                if ($retry)
                {
                    // We tried after unpacking at this point and still have nothing.
                    return null;
                }
                // There is a possible race condition here where our package processor
                // Could add an item and then attempt to get a new item before
                // the previous item had a chance to get packed. So the item could
                // exist at this point but no UID has been assigned.
                $this->unpack();                  // Attempt to unpack anything previous packed in a previous method but w/o being saved.
                $this->getUidByItem($item, true); // add retry so we don't get into a constant loop.
            }
        }
        return null;
    }

    /**
     * Remove an item by its logic ID (uid)
     * @param string $uid
     * @return void
     */
    public function removeItem(string $uid): void
    {
        $item = $this->getItem($uid);
        // Remove from items
        foreach ($this->items as $idx => $i)
        {
            if ($i->uid == $uid) unset($this->items[$idx]);
        }
        if ($item->type == 'services')
        {
            foreach ($this->services as $idx => $i)
            {
                if ($i->uid == $uid) unset($this->services[$idx]);
            }
        }
        else
        {
            foreach ($this->products as $idx => $i)
            {
                if ($i->uid == $uid) unset($this->products[$idx]);
            }
        }
        $this->pack();
    }

    /**
     * Increase the quantity of an item by uid.
     * @param string $uid
     * @return void
     * @throws LogicException
     */
    public function increaseQty(string $uid): void
    {
        $item = $this->getItem($uid);
        $req = $item->qty + 1;
        $this->checkOnHand($item->id, $req);
        $item->qty = $item->qty + 1;
        $this->updateItem($uid, $item);
    }

    /**
     * Decrease Quantity by 1 of an item in the cart.
     * @param string $uid
     * @return void
     */
    public function decreaseQty(string $uid): void
    {
        $item = $this->getItem($uid);
        $new = $item->qty - 1;
        if ($new < 1) $new = 1;
        $item->qty = $new;
        $this->updateItem($uid, $item);
    }

    /**
     * Update master items array with new item details
     * @param string $uid
     * @param object $item
     * @return void
     */
    public function updateItem(string $uid, object $item): void
    {
        foreach ($this->items as $idx => $i)
        {
            if ($i->uid == $item->uid)
            {
                $this->items[$idx] = $item;
            }
        }
        $this->pack();
    }

    /**
     * Apply a coupon from a component
     * @param Coupon $coupon
     * @return void
     */
    public function applyCoupon(Coupon $coupon): void
    {
        $this->coupon = $coupon;
        $this->processCoupon();
        $this->pack();
    }

    /**
     * Process Coupons
     * @return void
     */
    public function processCoupon(): void
    {
        // Check any updated items that may not have been affected by a coupon.
        if ($this->coupon && $this->coupon->new_accounts_only && user()) return; // Only for new accounts
        if ($this->coupon && $this->coupon->id && $this->total >= $this->coupon->dollar_spend_required)
        {
            if ($this->coupon->total_invoice) // Apply discounts to all items
            {
                foreach ($this->items as $item)
                {
                    if (!$item->couponApplied)
                    {
                        $newPrice = $this->coupon->getDiscountAmount($item->price);
                        $item->discountedAmount = $item->price - $newPrice;
                        $item->couponApplied = true;
                        $item->price = $newPrice;
                        $this->updateItem($item->uid, $item);
                    }
                }
            } // is total invoice?
            else
            {
                $ids = $this->coupon->items()->pluck('bill_item_id')->all();
                // We need to check each item to see if its in our list of available bill_item_ids.
                foreach ($this->items as $item)
                {
                    if (in_array($item->id, $ids) && !$item->couponApplied)
                    {
                        $newPrice = $this->coupon->getDiscountAmount($item->price);
                        $item->discountedAmount = $item->price - $newPrice;
                        $item->couponApplied = true;
                        $item->price = $newPrice;
                        $this->updateItem($item->uid, $item);
                    }
                }
            }
        } // if coupon can even run.
    } //fn

    /**
     * This will take an existing item in our cart and apply an addon based
     * on the option given.
     * @param string      $uid
     * @param AddonOption $option
     * @param int         $qty
     * @return void
     */
    public function applyAddon(string $uid, AddonOption $option, int $qty = 1): void
    {
        $item = $this->getItem($uid);
        if (!$item)
        {
            // Not sure how we'd get here. But someone will find a way.
            return;
        }
        if (!isset($item->appliedAddons) || !$item->appliedAddons)
        {
            $item->appliedAddons = collect([]); // init addons if this is the first entry into it.
        }
        $price = $option->price ?: $option->item->msrp;
        $item->appliedAddons->add((object)[
            'qty'       => $qty,
            'addon_id'  => $option->addon->id,
            'option_id' => $option->id,
            'price'     => $price,
            'msrp'      => $option->item->msrp,
            'text'      => sprintf("%s: %s", $option->addon->name, $option->name),
            'textPrice' => sprintf("%s: %s (+$%s)", $option->addon->name, $option->name, moneyFormat($price)),
            'option'    => $option->name,
            'group'     => $option->addon->name
        ]);
        $atotal = 0;
        foreach ($item->appliedAddons as $a)
        {
            $atotal += $a->price;
        }
        $item->addonTotal = $atotal;
        $this->updateItem($uid, $item); // Apply addon to item and repack.
    }

    /**
     * Take a request array and apply addons - This is done from the item component process and
     * takes a list of all selected addons as a whole during the add to cart process.
     * @param string $uid
     * @param array  $addons
     * @return void
     */
    public function processAddons(string $uid, array $addons): void
    {
        $item = $this->getItem($uid);
        $item->appliedAddons = collect([]);
        foreach ($addons as $add => $opt)
        {
            $add = str_replace("add_", '', $add);
            $add = (int)$add;
            $addon = Addon::find($add);
            $opt = $addon->options()->find($opt);
            $price = 0;
            $msrp = 0;
            if (!$opt) continue;
            if ($opt->price)
            {
                $price = $opt->price;
                if ($opt->item)
                {
                    $msrp = $opt->item->msrp;
                }
            }
            else
            {
                if ($opt->item)
                {
                    $price = $opt->item->type == 'services' ? $opt->item->mrc : $opt->item->nrc;
                    $msrp = $opt->item->msrp ?? $price;
                }
            }
            $item->appliedAddons->add((object)[
                'qty'       => 1,
                'addon_id'  => $addon->id,
                'option_id' => $opt->id,
                'price'     => $price,
                'msrp'      => $msrp,
                'text'      => sprintf("%s: %s", $addon->name, $opt->name),
                'textPrice' => sprintf("%s: %s (+$%s)", $addon->name, $opt->name, moneyFormat($price)),
                'option'    => $opt->name,
                'group'     => $addon->name
            ]);
        }
        // We need to update our item with the total
        $atotal = 0;
        foreach ($item->appliedAddons as $a)
        {
            $atotal += $a->price;
        }
        $item->addonTotal = $atotal;
        $this->updateItem($uid, $item);
    }

    // --- End of public methods --- //

    static public function setWorkingQuote(Quote $quote): void
    {
        session([CommKey::LocalQuoteSession->value => $quote]);
    }

    /**
     * Initialize a new cart for use by the cart/checkout system
     * @return void
     */
    private function init(): void
    {
        // Pack Default Values
        if (session(CommKey::LocalQuoteSession->value))
        {
            $this->quote = session(CommKey::LocalQuoteSession->value);
        }
        else $this->quote = new Quote();
        if (!$this->coupon) $this->coupon = new Coupon();
        $this->pack(); // This will set our class properties.. now we can sync the quote.
    }

    /**
     * Compile all private properties into the usable cart object
     * that can be used by all calling functions.
     * @return void
     */
    private function pack(): void
    {
        $this->setCrossSession();
        $this->populateTotals();
        // Repack Products and services
        $this->products = [];
        $this->services = [];
        foreach ($this->items as $item)
        {
            if ($item->type == 'services')
            {
                $this->services[] = $item;
            }
            else $this->products[] = $item;
        }
        $data = [];
        foreach ($this->tracked as $field)
        {
            $data[$field] = $this->{$field};
        }
        $cart = collect($data);
        $this->cart = $cart;
        session([CommKey::LocalCartSession->value => $cart]);
        $this->pushBus($cart);
    }

    /**
     * Unpack our session object and rebuild all our private
     * properties for usability.
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function unpack(): void
    {
        $cart = session(CommKey::LocalCartSession->value);
        foreach ($this->tracked as $field)
        {
            $this->{$field} = $cart->get($field);
        }
        $this->syncCartByQuote();
        $this->populateTotals();
    }

    /**
     * Final Method that will return the component a single cart
     * object it can use to render everything it needs.
     * @return object
     */
    public function getCart(): object
    {
        $this->pack();
        return $this->cart;
    }

    /**
     * Get all items and populate totals
     * @return void
     */
    private function populateTotals(): void
    {
        $this->total = 0;
        $this->totalMonthly = 0;
        $this->totalOne = 0;
        $addons = 0;

        // First see if we have a coupon applied and any items that should be tagged
        //

        foreach ($this->items as $item)
        {
            // Check for Existence of Addons and calculate accordingly
            if (isset($item->appliedAddons))
            {
                foreach ($item->appliedAddons as $addon)
                {
                    $addons += ($addon->price * $item->qty);
                }
            }

            if ($item->type == 'services')
            {
                $this->totalMonthly += ($item->price * $item->qty) + $addons;
            }
            else $this->totalOne += ($item->price * $item->qty) + $addons;
            $this->total += ($item->price * $item->qty) + $addons;
        }


    }

    /**
     * Execute a Cart/Quote, Everything
     *
     * No matter how this is done, a quote will be created and executed with the signature.
     * @param string $signName
     * @param string $signature
     * @return void
     */
    public function execute(string $signName, string $signature): void
    {
        // Step 1: Lets figure out which account we are working with. If we are a guest, we need
        // to create one. This should be refreshed and ready to go with our acls and everything.
        $account = $this->getAccount();
        $quote = $this->getQuote($account);
        $quote->reassignExecuted($account, $signName, $signature);
        if ($this->isNewAccount) $account->sendWelcomeEmail();
        $account->applyFromQuote($quote, true);
        auth()->loginUsingId($account->admin->id);
        if ($this->isNewAccount)
        {
            sysact(ActivityType::Account, $account->id, "executed Quote #{$quote->id} (New Customer) for ");
        }
        else
        {
            sysact(ActivityType::Account, $account->id, "executed Quote #{$quote->id} for ");
        }
        $quote->refresh();
        if ($quote->term)
        {
            $quote->sendSigned();
        }
        $cart = cart();
        $cart->destroy(); // Kill the cart.
    }

    /**
     * Create an account or return the one we are logged in as.
     * @return Account
     */
    private function getAccount(): Account
    {
        if (user()) return user()->account; // Already logged in

        // If there is a quote created, we may have a lead that we need to update.
        $agent = 0;
        if (session(CommKey::LocalQuoteSession->value))
        {
            $quote = session(CommKey::LocalQuoteSession->value);
            if ($quote->lead)
            {
                $lead = Lead::find($quote->lead->id);
                $lead->update(['active' => 0]); // Close lead.
                $agent = $lead->agent_id ?: 0;  // Set agent if this is from a pre-existing quote.
            }
        }
        $account = (new Account)->create([
            'name'           => $this->infoData['company'],
            'address'        => $this->infoData['address'],
            'address2'       => $this->infoData['address2'] ?? null,
            'city'           => $this->infoData['city'],
            'state'          => $this->infoData['state'],
            'postcode'       => $this->infoData['zip'],
            'country'        => 'US',
            'phone'          => $this->infoData['phone'],
            'active'         => 1,
            'agent_id'       => $agent,
            'logo_id'        => 0,
            'next_bill'      => now(),
            'bills_on'       => now()->day,
            'net_days'       => (int)setting('invoices.net') ?: 0,
            'payment_method' => setting('invoices.default'),
            'guest_created'  => true
        ]);
        $account->generateHash();

        $user = (new User)->create([
            'name'       => $this->infoData['contact'],
            'email'      => $this->infoData['email'],
            'password'   => bcrypt($this->infoData['password']),
            'account_id' => $account->id,
            'acl'        => ACL::ADMIN,
            'active'     => 1,
            'is_agent'   => 0
        ]);
        $user->authorizeIp();
        $user->update(['email_verified_at' => now()]);
        $account->refresh();
        return $account;
    }

    /**
     * Create a Quote Based on a Cart.
     * NOTE: This will not have an account OR a lead set. This will have to be set
     * manually by whatever is calling this method.
     * @return Quote
     */
    public function createQuoteFromCart(): Quote
    {
        $quote = new Quote();
        // Reassignable Properties
        $quote->account_id = 0;
        $quote->lead_id = 0;
        $quote->name = "New Quote";
        $quote->hash = uniqid('QO-');
        $quote->save();
        $quote->refresh();
        $this->applyCartItemsToQuote($quote);
        $quote->refresh();
        return $quote;
    }

    /**
     * Take Cart items and apply them to a quote.
     * @param Quote $quote
     * @return void
     */
    public function applyCartItemsToQuote(Quote $quote): void
    {
        $cart = cart();
        foreach ($cart->items as $i)
        {
            $this->deductFromOnHand($i);
            $reservedText = $i->reservation_mode ? " ** RESERVATION **" : null;
            $item = $quote->items()->create([
                'item_id'     => $i->id,
                'price'       => $i->price,
                'qty'         => $i->qty,
                'description' => $i->description . $reservedText
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
        $quote->save();
    }

    /**
     * Get the quote. If guest then create a quote assign it to the account
     * and return it.
     * @param Account $account
     * @return Quote
     */
    private function getQuote(Account $account): Quote
    {
        if (session(CommKey::LocalQuoteSession->value))
        {
            $quote = session(CommKey::LocalQuoteSession->value);
            $quote = Quote::find($quote->id);
            if (!$quote->id) $quote = $this->createQuoteFromCart();
        }
        else
        {
            $quote = $this->createQuoteFromCart();
        }
        $quote->refresh();
        // Step 1: Update quote with our new account id if it doesn't exist.
        $quote->account_id = $account->id;
        // Step 2: Assign name
        $quote->name = $quote->name ?: "New Order for $account->name";
        $quote->preferred = true;
        $quote->sent_on = now();
        if ($this->coupon && $this->coupon->id)
        {
            $quote->coupon_id = $this->coupon->id; // Set coupon for commission tracking for affiliate.
            if ($this->coupon->affiliate)
            {
                // Update account with the affiliate assignment.
                $account->update(['affiliate_id' => $this->coupon->affiliate->id]);
            }
        }

        if (session(CommKey::LocalPackageAnswerSession->value))
        {
            $quote->package_answers = session(CommKey::LocalPackageAnswerSession->value);
            Session::forget(CommKey::LocalPackageAnswerSession->value);
        }
        if (!$quote->term)
        {
            // Either there is no term, or we have a term scheduled in session.
            if (session(CommKey::LocalContractSession->value))
            {
                $term = (int)session(CommKey::LocalContractSession->value);
                if ($term > 0)
                {
                    $quote->term = $term;
                }
            }
        }
        $quote->net_terms = $quote->net_terms ?: (int)setting('invoices.net');
        $quote->save();
        $quote->refresh();
        return $quote;
    }

    /**
     * This method will be called when we are increasing our quantity
     * or simply trying
     * @param int $item_id
     * @param int $qtyRequested
     * @return void
     * @throws LogicException
     */
    private function checkOnHand(int $item_id, int $qtyRequested): void
    {
        $item = BillItem::find($item_id);
        // If we are tracking qty we need to make sure we have enough to sell
        // or if not that we are allowed to backorder.
        if ($item->track_qty)
        {
            if ($item->on_hand < $qtyRequested && !$item->allow_backorder)
            {
                throw new LogicException("Inventory Unavailable. Quantity Requested: $qtyRequested.");
            }
        }
    }

    /**
     * This will take a modified bill item and deduct the official quantity
     * if we are tracking the values. This is done on execution
     * @param object $i
     * @return void
     */
    private function deductFromOnHand(object $i): void
    {
        $item = BillItem::find($i->id);
        if ($item->track_qty)
        {
            $item->update(['on_hand' => $item->on_hand - $i->qty]);
        }
    }

    /**
     * This method will store a contract term set by guests when checking out
     * with services. This will apply discounted pricing to the price item.
     *
     * @param string $term
     * @return void
     */
    public function setGuestContractTerm(string $term): void
    {
        session([CommKey::LocalContractSession->value => $term]);
        foreach ($this->items as $item)
        {
            // First lets see if we have a discount for the term provided
            $item = $this->getItem($item->uid);

            if ($term != '0' && isset($item->discountTerm->{$term}))
            {
                $perc = $item->discountTerm->{$term};
                $start = $item->priceBeforeContract;
                $disc = ($start * ($perc / 100));
                $item->price = $start - $disc;
            }
            else
            {
                $item->price = $item->priceBeforeContract;
            }
            $this->updateItem($item->uid, $item);
        }
    }

    /**
     * Remove all items in the cart but don't destroy the
     * session.
     * @return void
     */
    public function removeAll(): void
    {
        $this->items = [];
        $this->pack();
    }

    /**
     * Destroy working quote
     * @return void
     */
    public function destroy(): void
    {
        Session::forget(CommKey::LocalCartSession->value);
        Session::forget(CommKey::LocalQuoteSession->value);
        Session::forget(CommKey::LocalContractSession->value);
    }

    /**
     * Creates a session that is sync'd with our sbus
     * @return string
     */
    public function setCrossSession(): string
    {
        $uid = session(CommKey::LocalCartCrossSession->value);
        if (!$uid)
        {
            $uid = uniqid('CART-');
            $this->uid = $uid;
        }
        $this->uid = $uid;
        session([CommKey::LocalCartCrossSession->value => $uid]);
        return $uid;
    }

    /**
     * This will keep our cart active state to an admin
     * @param Collection $cart
     * @return void
     */
    private function pushBus(Collection $cart): void
    {
        if (!$this->uid) $this->setCrossSession();
        sbus()->receive($this->uid, $cart);
    }

}
