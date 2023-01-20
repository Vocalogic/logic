<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $logics
 */
class PackageSectionQuestion extends Model
{
    protected $guarded = ['id'];

    /**
     * A question belongs to a section
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(PackageSection::class, 'package_section_id');
    }

    /**
     * A question has many logical steps to add to a cart based on input.
     * @return HasMany
     */
    public function logics(): HasMany
    {
        return $this->hasMany(PackageSectionQuestionLogic::class, 'package_section_question_id');
    }

    /**
     * A question can have many options if a dropdown or multi-input form.
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(PackageSectionQuestionOption::class, 'package_section_question_id');
    }

    /**
     * Get a selectable array of questions for using unless methods.
     * @return array
     */
    static public function getSelectable(): array
    {
        $data = [];
        $data[''] = '-- Select Question --';
        foreach (self::orderBy('question')->get() as $q)
        {
            $data[$q->id] = sprintf("%s (%s)", $q->question, $q->section->name);
        }
        return $data;
    }

    /**
     * Get all qualifier methods
     * @return array
     */
    static public function getEquates(): array
    {
        $data = [];
        $data[''] = '-- Select Qualifier --';
        $data['equals'] = "equals";
        $data['greater'] = "greater than";
        $data['less'] = "less than";
        $data['notequals'] = "does not equal";
        $data['exists'] = 'has an answer';
        $data['notexists'] = "was not answered";
        return $data;
    }

    /**
     * The types of answers allowed.
     * @return array
     */
    static public function getTypes(): array
    {
        $data = [];
        $data[''] = '-- Select Type --';
        $data['text'] = 'Input Box (Text)';
        $data['select'] = "Dropdown (Select)";
        $data['textarea'] = "Large Input Box";
        $data['multi'] = "Multi-Input";
        $data['product'] = 'Product Select';
        return $data;
    }
}
