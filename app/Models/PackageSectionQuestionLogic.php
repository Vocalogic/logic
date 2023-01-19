<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $addedItem
 */
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

    /**
     * This pivots to an addon option but is named addon for the
     * context of the package section logic.
     * @return BelongsTo
     */
    public function addon(): BelongsTo
    {
        return $this->belongsTo(AddonOption::class, 'add_addon_id');
    }
}
