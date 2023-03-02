<?php

namespace App\Models;

use App\Enums\Core\ProjectStatus;
use App\Enums\Core\ThreadType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsTo(ProjectTaskEntry::class);
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
}
