<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $sections
 */
class PackageBuild extends Model
{
    protected $guarded = ['id'];

    /**
     * A package has many sections (or steps)
     * @return HasMany
     */
    public function sections(): HasMany
    {
        return $this->hasMany(PackageSection::class, 'package_build_id');
    }

    /**
     * Return a list of addons from products/services already in this question logic.
     * Do not return all as it will just make the dropdown more convoluted.
     * @return array
     */
    public function relatedAddons(): array
    {
        $data = [];
        $data[''] = '-- Select Addon --';
        foreach($this->sections as $section)
        {
            foreach ($section->questions as $question)
            {
                $qName = $question->question;
                foreach ($question->logics()->whereNotNull('add_item_id')->get() as $logic)
                {
                    $addons = [];
                    if ($logic->addedItem)
                    {
                        foreach ($logic->addedItem->addons as $addon)
                        {
                            $name = $addon->name;
                            foreach($addon->options as $option)
                            {
                                $addons[$option->id] = sprintf("(%s) %s - %s", $qName,  $name, $option->name);
                            }
                        }
                        if (sizeOf($addons))
                        {
                            $data[$logic->addedItem->name] = $addons;
                        }
                    }
                }
            }
        }
        return $data;
    }

}
