<?php

namespace App\Models;

use App\Enums\Core\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderLog extends Model
{
    protected $guarded = ['id'];

    public $casts = ['status' => OrderStatus::class];
    /**
     * A log entry belongs to an order.
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

}
