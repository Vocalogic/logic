<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageSectionQuestionOption extends Model
{
    protected $guarded = ['id'];

    /**
     * An option belongs to a question.
     * @return BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(PackageSectionQuestion::class, 'package_section_question_id');
    }

    /**
     * Get Item by Option (for product list)
     * @return BillItem|null
     */
    public function getTag(): ?Tag
    {
        return Tag::find($this->option);
    }
}
