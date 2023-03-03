<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThreadComment extends Model
{
    protected $guarded = ['id'];

    /**
     * A comment belongs to a thread
     * @return BelongsTo
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * A comment can have nested comments. (single level)
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(ThreadComment::class, 'thread_comment_id');
    }

    /**
     * A thread comment can have many uploaded files.
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(ThreadCommentFile::class, 'comment_id');
    }

    /**
     * A comment belongs to a user.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
