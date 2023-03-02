<?php

namespace App\Models;

use App\Enums\Core\ThreadType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    protected $guarded = ['id'];
    public $casts = ['type' => ThreadType::class];


    /**
     * A thread has many comments
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ThreadComment::class);
    }

    /**
     * Who created this thread?
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
