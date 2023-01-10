<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageSectionQuestionLogic extends Model
{
    protected $guarded = ['id'];

    /**
     * A logical operation belongs to a question.
     * @return BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(PackageSectionQuestion::class, 'package_section_question_id');
    }

    /**
     * The item to be added
     * @return BelongsTo
     */
    public function addedItem() : BelongsTo
    {
        return $this->belongsTo(BillItem::class, 'add_item_id');
    }
}
