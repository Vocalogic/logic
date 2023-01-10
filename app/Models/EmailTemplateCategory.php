<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailTemplateCategory extends Model
{
    protected $guarded = ['id'];

    /**
     * A category has many templates.
     * @return HasMany
     */
    public function templates():HasMany
    {
        return $this->HasMany(EmailTemplate::class);
    }

}
