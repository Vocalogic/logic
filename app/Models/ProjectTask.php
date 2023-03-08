<?php

namespace App\Models;

use App\Enums\Core\ProjectStatus;
use App\Enums\Core\ThreadType;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $bill_method
 * @property mixed $static_price
 * @property mixed $est_hours_min
 * @property mixed $task_hourly_rate
 * @property mixed $est_hours_max
 * @property mixed $entries
 */
class ProjectTask extends Model
{
    protected $guarded = ['id'];
    public    $casts   = [
        'status' => ProjectStatus::class
    ];

    /**
     * A task can belong to a project directly (no category)
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * A task can belong to a category.
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    /**
     * Who is assigned to this task?
     * @return BelongsTo
     */
    public function assigned(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A task has many time entries logged.
     * @return HasMany
     */
    public function entries(): HasMany
    {
        return $this->hasMany(ProjectTaskEntry::class);
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
     * Get the minimum total for the item
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
        $total += (int)bcmul($this->est_hours_min * $this->task_hourly_rate, 1);
        return $total;
    }

    /**
     * Get the maximum total for the item
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
        $total += (int)bcmul($this->est_hours_max * $this->task_hourly_rate, 1);
        return $total;
    }

    /**
     * Calculates the number of hours into hours/minutes
     * @return string
     */
    public function getTimeAttribute(): string
    {
        $totalHours = 0;
        foreach ($this->entries as $entry)
        {
            $totalHours += $entry->hours;
        }
        // To show this we will set our start time to now - end + totalHours
        $start = now();
        $end = now()->addHours($totalHours);
        $options = [
            'join'   => ', ',
            'parts'  => 2,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
        ];
        if ($end->diffInSeconds($start) < 60) return "None";
        return $end->diffForHumans($start, $options);
    }

    /**
     * Get the amount currently worked that has not been billed.
     * @return int
     */
    public function getUnbilledTimeAttribute(): int
    {
        $total = 0;
        foreach ($this->entries()->whereNull('invoice_id')->get() as $entry)
        {
            $total += bcmul($entry->hours * $this->task_hourly_rate,1);
        }
        return $total;
    }

}
