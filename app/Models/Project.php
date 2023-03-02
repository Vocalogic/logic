<?php

namespace App\Models;

use App\Enums\Core\ProjectStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $guarded = ['id'];
    public    $casts   = [
        'sent_on'     => 'datetime',
        'due_on'      => 'datetime',
        'approved_on' => 'datetime',
        'status'      => ProjectStatus::class
    ];

    /**
     * A project can belong to an account (when sold or already existing customer)
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * A project can belong to a lead in pre-sales
     * @return BelongsTo
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * A project has many categories of tasks
     * @return HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(ProjectCategory::class);
    }

    /**
     * A project can have tasks that are not categorized.
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }

    /**
     * Who created this project? or who manages it when working?
     * @return BelongsTo
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
