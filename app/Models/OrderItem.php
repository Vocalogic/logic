<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    protected $guarded = ['id'];

    /**
     * An item belongs to an order
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * An item can link directly to a product or service.
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(BillItem::class, 'bill_item_id');
    }

    /**
     * An item can be shipped. If so needs a shipment to link to.
     * @return BelongsTo
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * An item can be assigned to a user.
     * @return BelongsTo
     */
    public function assigned(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_id');
    }

    /**
     * Items have many notes.
     * @return HasMany
     */
    public function notes(): HasMany
    {
        return $this->hasMany(OrderNote::class, 'order_item_id');
    }

}
