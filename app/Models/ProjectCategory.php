<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectCategory extends Model
{
    protected $guarded = ['id'];

    /**
     * A category belongs to a project
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * A category has many tasks.
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }

    /**
     * A category has many billable items.
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(ProjectCategoryItem::class, 'project_category_id');
    }


}
