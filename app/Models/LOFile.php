<?php

namespace App\Models;

use App\Traits\HasLogTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $id
 * @property mixed $mime_type
 * @property mixed $type
 * @property mixed $filename
 * @property mixed $filesize
 */
class LOFile extends Model
{
    use HasLogTrait;

    protected $guarded = ['id'];
    public    $table   = "lo_files";


    /**
     * A file can belong to a category.
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FileCategory::class, 'file_category_id');
    }

}
