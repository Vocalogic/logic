<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThreadCommentFile extends Model
{
    protected $guarded = ['id'];

    /**
     * A file belongs to a comment.
     * @return BelongsTo
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(ThreadComment::class, 'comment_id');
    }

    /**
     * Link directly to file uploaded.
     * @return BelongsTo
     */
    public function file() : BelongsTo
    {
        return $this->belongsTo(LOFile::class, 'file_id');
    }
}
