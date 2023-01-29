<?php

namespace App\Models;

use App\Structs\STemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use League\CommonMark\CommonMarkConverter;

/**
 * @property mixed $body
 */
class Term extends Model
{
    protected $guarded = ['id'];

    /**
     * A term belongs to a lead type
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(LeadType::class, 'lead_type_id');
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
