<?php

namespace App\Models;

use App\Enums\Core\ThreadType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $static_price
 * @property mixed $items
 * @property mixed $tasks
 * @property mixed $bill_method
 * @property mixed $totalHoursMax
 * @property mixed $totalWorked
 * @property mixed $completedTasks
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

    public function getTotalHoursMinAttribute(): int
    {
        $total = 0;
        foreach ($this->tasks as $task)
        {
            $total += $task->est_hours_min;
        }
        return $total;
    }

    public function getTotalHoursMaxAttribute(): int
    {
        $total = 0;
        foreach ($this->tasks as $task)
        {
            $total += $task->est_hours_max;
        }
        return $total;
    }

    public function getTotalWorkedAttribute(): int
    {
        $total = 0;
        foreach ($this->tasks as $task)
        {
            $total += $task->totalWorked;
        }
        return $total;
    }

    /**
     * Get total billed amount for a category
     * @return int
     */
    public function getTotalBilledAttribute(): int
    {
        $total = 0;
        foreach ($this->tasks as $task)
        {
            $total += $task->totalBilled;
        }
        return $total;
    }

    public function getProgressAttribute(): int
    {
        if (!$this->totalHoursMax) return 0;
        return round($this->totalWorked / $this->totalHoursMax * 100);
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

    /**
     * Get number of comments from a thread on this task.
     * @return int
     */
    public function getCommentsAttribute(): int
    {
        $thread = Thread::where('type', ThreadType::getByModel($this::class))
            ->where('refid', $this->id)->first();
        if (!$thread) return 0;
        return $thread->comments()->count();
    }

    /**
     * Get the icon to show for the category depending on the state. 
     * @return string
     */
    public function getIconAttribute(): string
    {
        $icon = 'ri-pencil-line';
        if ($this->tasks->where('completed', false)->count() == 0)
        {
            $icon = 'ri-edit-2-fill';
        }
        if ($this->totalWorked > $this->totalHoursMax)
        {
            $icon = 'ri-archive-fill';
        }
        return $icon;
    }

    /**
     * Get unbilled time by category
     * @return int
     */
    public function getUnbilledTimeAttribute(): int
    {
        $total = 0;
        foreach ($this->tasks as $task)
        {
            $total += $task->unbilledTime;
        }
        return $total;
    }


}
