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
    public $casts = [
        'start_date'  => 'datetime',
        'end_date'    => 'datetime'
    ];
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
        foreach ($this->items as $item)
        {
            $total += (int)bcmul($item->price * $item->qty, 1);
        }
        foreach ($this->tasks as $task)
        {
            $total += $task->totalMin;
        }
        return $total;
    }

    /**
     * Get the max total for the category
     * @return int
     */
    public function getTotalMaxAttribute(): int
    {
        $total = 0;
        if ($this->bill_method == 'Mixed' || $this->bill_method == 'Static')
        {
            $total += $this->static_price;
        }
        if ($this->bill_method == 'Static') return $total;
        foreach ($this->items as $item)
        {
            $total += (int)bcmul($item->price * $item->qty, 1);
        }
        foreach ($this->tasks as $task)
        {
            $total += $task->totalMax;
        }
        return $total;
    }

    /**
     * Get total expense max
     * @return int
     */
    public function getTotalExpenseMaxAttribute(): int
    {
        $total = 0;
        foreach ($this->items as $item)
        {
            if ($item->item && $item->item->ex_capex)
            {
                $total += bcmul($item->item->ex_capex * $item->qty, 1);
            }
            else
            {
                $total += bcmul($item->expense * $item->qty, 1);
            }
        }
        return $total;
    }

    /**
     * Return the number of completed tasks
     * @return int
     */
    public function getCompletedTasksAttribute() : int
    {
        return $this->tasks()->where('completed', true)->count();
    }

    /**
     * Get the percentage of complete tasks.
     * @return int
     */
    public function getCompletedPercentageAttribute(): int
    {
        if ($this->tasks->count() == 0) return 0;
        $val = $this->getCompletedTasksAttribute() / $this->tasks->count();
        return round($val * 100);
    }


}
