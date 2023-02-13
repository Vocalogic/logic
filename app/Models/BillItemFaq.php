<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillItemFaq extends Model
{
    protected $guarded = ['id'];

    /**
     * Define our array of tracked changes. This will be used for the
     * logging class to optional compare a previous instance of an
     * object before it was changed and print human-readable changes.
     * @var array
     */
    public array $tracked = [
      'question'  => "Question",
      'answer'    => "Answer",
    ];

    /**
     * A FAQ belongs to a bill item.
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(BillItem::class, 'bill_item_id');
    }

}
