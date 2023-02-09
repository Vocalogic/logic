<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer     $user_id
 * @property integer     $account_id
 * @property string      $type
 * @property integer     $type_id
 * @property integer     $log_level
 * @property string     $log
 * @property string     $detail
 */
class AppLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * A log entry can be attached to a user.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A log entry can be attached to an account/
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Auto convert into a json object IF the data found
     * is in fact a json object. Otherwise just return the text.
     * @return mixed
     */
    public function getDetailFormattedAttribute(): mixed
    {
        if (json_decode($this->detail)) return json_decode($this->detail);
        return $this->detail;
    }

    /**
     * Get the parent loggable model (invoice, quote and etc).
     */
    public function loggable()
    {
        return $this->morphTo(__FUNCTION__, 'type', 'type_id');
    }
}
