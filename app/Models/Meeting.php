<?php

namespace App\Models;

use App\Enums\Core\MeetingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    protected $guarded = ['id'];
    protected $casts   = [
        'type'         => MeetingType::class,
        'starts'       => 'datetime',
        'ends'         => 'datetime',
        'sent_on'      => 'datetime',
        'confirmed_on' => 'datetime'
    ];

    /**
     * A meeting belongs to an account.
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * A meeting can have a parent (recurring spawn)
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Meeting::class, 'parent_id');
    }

    /**
     * A meeting can spawn many meetings from a recurring setting.
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Meeting::class, 'parent_id');
    }

}
