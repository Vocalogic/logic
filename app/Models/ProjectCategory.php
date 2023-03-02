<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $static_price
 * @property mixed $items
 * @property mixed $tasks
 * @property mixed $bill_method
 */
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

    /**
     * Get the total for the category
     * @return int
     */
    public function getTotalMinAttribute(): int
    {
        $total = 0;
        if ($this->bill_method == 'Mixed' || $this->bill_method == 'Static')
        {
            $total += $this->static_price;
        }
        if ($this->bill_method == 'Static') return $total;
        foreach($this->items as $item)
        {
            $total += (int) bcmul($item->price * $item->qty,1);
        }
        foreach ($this->tasks as $task)
        {
            $total += $task->totalMin;
        }
        return $total;
    }

}
