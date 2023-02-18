<?php

namespace App\Models;

use App\Traits\HasLogTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $id
 */
class BillItemMeta extends Model
{
    use HasLogTrait;

    public $table = 'bill_item_meta';
    protected $guarded = ['id'];

    /**
     * Define our array of tracked changes. This will be used for the
     * logging class to optional compare a previous instance of an
     * object before it was changed and print human-readable changes.
     * @var array
     */
    public array $tracked = [
      'item'           => "Name",
      'answer_type'    => "Answer Type",
      'description'    => "Help Text",
    ];

    /**
     * Metadata belongs to an item.
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(BillItem::class);
    }

}
