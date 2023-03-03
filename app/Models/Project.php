<?php

namespace App\Models;

use App\Enums\Core\ProjectStatus;
use App\Operations\Core\MakePDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $categories
 * @property mixed $static_price
 * @property mixed $bill_method
 */
class Project extends Model
{
    protected $guarded = ['id'];
    public    $casts   = [
        'sent_on'     => 'datetime',
        'due_on'      => 'datetime',
        'approved_on' => 'datetime',
        'status'      => ProjectStatus::class,
        'start_date'  => 'datetime',
        'end_date'    => 'datetime'
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

    /**
     * Return a streamed PDF.
     * @param bool $save
     * @return mixed
     */
    public function pdf(bool $save = false): mixed
    {
        $pdf = new MakePDF();
        $pdf->setName("Project-$this->id.pdf");
        $data = view("pdf.projects.project")->with('project', $this)->render();
        if (!$save)
        {
            return $pdf->streamFromData($data);
        }
        else return storage_path() . "/" . $pdf->saveFromData($data);
    }


    /**
     * Get the total for the category
     * @return int
     */
    public function getTotalMinAttribute(): int
    {
        $total = 0;
        if ($this->bill_method == 'Static')
        {
            return $this->static_price;
        }
        foreach ($this->categories as $category)
        {
            $total += $category->totalMin;
        }
        $total += $this->static_price;
        return $total;
    }

    /**
     * Get the max total for the category
     * @return int
     */
    public function getTotalMaxAttribute(): int
    {
        $total = 0;
        if ($this->bill_method == 'Static')
        {
            return $this->static_price;
        }
        foreach ($this->categories as $category)
        {
            $total += $category->totalMax;
        }
        $total += $this->static_price;
        return $total;
    }

    /**
     * Get total expense max
     * @return int
     */
    public function getTotalExpenseMaxAttribute(): int
    {
        $total = 0;
        foreach ($this->categories as $category)
        {
            $total += $category->totalExpenseMax;
        }
        return $total;
    }

}
