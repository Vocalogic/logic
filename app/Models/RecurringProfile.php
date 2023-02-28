<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecurringProfile extends Model
{
    protected $guarded = ['id'];
    public $casts = ['next_bill' => 'datetime'];
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

    /**
     * Get a selectable array of billing profiles for an account
     * @param Account $account
     * @return array
     */
    static public function getSelectable(Account $account): array
    {
        $data = [];
        $data[''] = 'Default Monthly Invoice';
        foreach($account->recurringProfiles as $profile)
        {
            $data[$profile->id] = sprintf("#%d - %s", $profile->id, $profile->name);
        }
        return $data;
    }

}
