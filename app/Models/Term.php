<?php

namespace App\Models;

use App\Structs\STemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use League\CommonMark\CommonMarkConverter;

/**
 * @property mixed $body
 * @property mixed $name
 */
class Term extends Model
{
    protected $guarded = ['id'];

    /**
     * A TOS can be assigned to many bill items.
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(BillItem::class, 'tos_id');
    }

    /**
     * Return markdown to html for body.
     * @param array $models
     * @return string
     */
    public function convert(array $models) : string
    {
        $e = new STemplate(
            ident: $this->body,
            user: null,
            models: $models,
        );
        return $e->contentBody;

    }
}
