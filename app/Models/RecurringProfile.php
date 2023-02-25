<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecurringProfile extends Model
{
    protected $guarded = ['id'];

    /**
     * A recurring profile belongs to an account
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * A recurring profile has many account items.
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(AccountItem::class);
    }

}
