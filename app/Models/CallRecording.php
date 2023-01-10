<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $from
 * @property mixed $to
 * @property mixed $time_open
 * @property mixed $url
 */
class CallRecording extends Model
{
    protected $guarded = ['id'];
    public $casts = [
        'time_open' => 'datetime'
    ];

    /**
     * A recording belongs to an account.
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

}
