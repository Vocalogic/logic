<?php

namespace App\Models;

use App\Enums\Core\ActivityType;
use App\Enums\Core\IntegrationType;
use App\Jobs\DispatchChat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed        $user_id
 * @property mixed        $refid
 * @property mixed|string $type
 * @property Carbon|mixed $event
 * @property mixed|string $post
 * @property mixed|void   $image_id
 * @property mixed        $lead
 * @property mixed        $account
 * @property mixed        $order
 * @property mixed        $system
 * @property mixed        $user
 * @property mixed        $activity
 * @property bool|mixed   $private
 * @property mixed        $partner
 * @property mixed        $formatted
 */
class Activity extends Model
{
    protected $guarded = ['id'];
    public    $casts   = ['type' => ActivityType::class, 'verb' => ActivityType::class, 'event' => 'datetime'];


    /**
     * Activity was done by a user.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get summary from type
     * @return string
     */
    public function getSummaryAttribute(): string
    {
        return $this->type->getSummary($this);
    }

    /**
     * Event can be bound to a lead
     * @return BelongsTo
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'refid');
    }

    /**
     * A message can be from a partner.
     * @return BelongsTo
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Event can be bound to an order
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'refid');
    }

    /**
     * Event could be bound to an account
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'refid');
    }

    /**
     * Send the notification to the appropriate chat channel
     * if chat integration has been integrated.
     * @return void
     */
    public function sendNotification(): void
    {
        if (!hasIntegration(IntegrationType::Chat)) return; // no integration configured
        dispatch(new DispatchChat($this));
    }

    /**
     * This creates a summary of the activity for things such as chat/email notifications.
     * @return string
     */
    public function getFormattedAttribute(): string
    {
        return $this->type->getSummary($this);
    }


}
