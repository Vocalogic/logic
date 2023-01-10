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

}
