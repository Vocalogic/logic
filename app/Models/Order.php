<?php

namespace App\Models;

use App\Enums\Core\BillItemType;
use App\Enums\Core\OrderStatus;
use App\Traits\HasLogTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $id
 * @property mixed $hash
 * @property mixed $account
 * @property mixed $provisioning
 * @property mixed $shipments
 * @property mixed $items
 * @property mixed $updated_at
 */
class Order extends Model
{
    use HasLogTrait;
    
    protected $guarded = ['id'];
    public $casts = ['status' => OrderStatus::class];

    /**
     * An order can just have an invoice (post lead)
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * An order has many log entries
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(OrderLog::class, 'order_id');
    }

    /**
     * An order belongs to an account
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * An order has many items
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }


    /**
     * Get customer link to order
     * @return string
     */
    public function getLinkAttribute(): string
    {
        return sprintf("%s/shop/account/order/%s", setting('brand.url'), $this->hash);
    }

    /**
     * An order has many shipments
     * @return HasMany
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * Has this order been .... something
     * @param OrderStatus $status
     * @return bool
     */
    public function hasBeen(OrderStatus $status) : bool
    {
        return (bool) $this->logs()->where('status', $status)->count();
    }

    /**
     * Get the order log based on a status
     * @param OrderStatus $status
     * @return OrderLog|null
     */
    public function whereWas(OrderStatus $status) : ?OrderLog
    {
        return $this->logs()->where('status', $status)->first();
    }

    /**
     * Does this order have shippable products?
     * @return bool
     */
    public function hasShippable(): bool
    {
        $shippable = false;
        foreach ($this->items as $item)
        {
            if ($item->item && $item->item->is_shipped) return true;
        }
        return false;
    }


    /**
     * Send email to customer with link
     * @return void
     */
    public function send(): void
    {
        template('account.customer_order', $this->account->admin, [$this]);
    }

    /**
     * Get selectable shipments from this order.
     * @return array
     */
    public function shipmentSelectable(): array
    {
        $shipments = [];
        foreach ($this->shipments as $shipment)
        {
            $shipments[$shipment->id] = sprintf("Shipment #%d (%s)", $shipment->id,
                $shipment->vendor ? $shipment->vendor->name : "Unknown");
        }
        return $shipments;
    }

    /**
     * Get First Image for Customer Order View
     * @return int|null
     */
    public function getFirstImage(): ?int
    {
        foreach ($this->items as $item)
        {
            if ($item->item && $item->item->photo_id)
                return $item->item->photo_id;
        }
        return null;
    }

    /**
     * Get tracking if exists for the first shipment
     * @return mixed
     */
    public function getFirstShipment() : mixed
    {
        if (!$this->shipments()->count()) return null;
        return $this->shipments()->first();
    }

    /**
     * Does this lead require an update?
     * @return bool
     */
    public function getRequiresUpdateAttribute(): bool
    {
        $setting = setting('orders.stale');
        if (!$setting) return false;
        $diff = $this->updated_at->diffInDays();
        return $diff > (int)$setting;
    }

    /**
     * Get all orders for an account and organize the keys by year
     * @param Account $account
     * @return array
     */
    static public function ordersByYear(Account $account) : array
    {
        $years = [];
        foreach ($account->orders()->orderBy('created_at', 'DESC')->get() as $order)
        {
            $year = $order->created_at->format("Y");
            if (!isset($years[$year]))
            {
                $years[$year] = [];
            }
            $years[$year][] = $order;
        }
        return $years;
    }



}
