<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PackageSection extends Model
{
    protected $guarded = ['id'];

    /**
     * A section (or step) belongs to a build
     * @return BelongsTo
     */
    public function build(): BelongsTo
    {
        return $this->belongsTo(PackageBuild::class, 'package_build_id');
    }

    /**
     * A section has many questions
     * @return HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(PackageSectionQuestion::class, 'package_section_id');
    }
}
