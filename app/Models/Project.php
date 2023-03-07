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
 * @property mixed $hash
 * @property mixed $lead
 * @property mixed $end_date
 * @property mixed $start_date
 * @property mixed $totalMax
 * @property mixed $totalMin
 * @property mixed $account
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
     * Get company based on stage of project.
     * @return string
     */
    public function getCompanyAttribute(): string
    {
        return $this->lead ? $this->lead->company : $this->account->name;
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

    /**
     * Get a selectable list of assignees for a task.
     * @return array
     */
    public function getAssignees(): array
    {
        $users = [];
        $users[0] = '-- Select Assignee --';
        foreach (User::where('account_id', 1)->get() as $user)
        {
            $users[$user->id] = $user->name;
        }
        return $users;
    }

    /**
     * Get start date for email template/msa
     * @return string
     */
    public function getStartHumanAttribute(): string
    {
        return $this->start_date ? $this->start_date->format("M d, Y") : "Undefined";
    }

    /**
     * Get end date for MSA/email templates
     * @return string
     */
    public function getEndHumanAttribute(): string
    {
        return $this->end_date ? $this->end_date->format("M d, Y") : "Undefined";
    }

    public function getEstMinAttribute(): string
    {
        return "$". moneyFormat($this->totalMin);
    }

    public function getEstMaxAttribute(): string
    {
        return "$". moneyFormat($this->totalMax);
    }

    /**
     * Send project for approval/review.
     * @return void
     */
    public function send(): void
    {
        $this->update(['sent_on' => now()]);
        if ($this->lead)
        {
            template('lead.projectReview', null, [$this], [$this->pdf(true)], $this->lead->email, $this->lead->contact);
        }
    }

    /**
     * Get a link for the email template.
     * @return string
     */
    public function getLinkAttribute(): string
    {
        $host = setting('brand.url');
        if ($this->lead)
        {
            return sprintf("%s/shop/presales/%s/projects/%s", $host, $this->lead->hash, $this->hash);
        }
    }

}
