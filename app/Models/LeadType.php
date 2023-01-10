<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property mixed $id
 */
class LeadType extends Model
{
    protected $guarded = ['id'];


    /**
     * Return a encoded list of x-editable
     * @return string
     */
    static public function encodedList(): string
    {
        $list = self::all();
        $data = [];
        foreach ($list as $type)
        {
            $data[$type->id] = $type->name;
        }
        return json_encode($data);
    }

    /**
     * A lead type has many questions
     * @return HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Discovery::class, 'lead_type_id');
    }

    /**
     * Lead types have one terms of service record.
     * @return HasOne
     */
    public function term() : HasOne
    {
        return $this->hasOne(Term::class);
    }

    /**
     * Get selectable array for lead types.
     * @return array
     */
    static public function getSelectable(): array
    {
        $data = [];
        $data[''] = '-- Select Lead Type --';
        foreach (self::orderBy('name')->get() as $type)
        {
            $data[$type->id] = $type->name;
        }
        return $data;
    }
}
