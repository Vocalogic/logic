<?php

namespace App\Models;

use App\Enums\Core\EventType;
use Illuminate\Database\Eloquent\Model;

class LOLog extends Model
{
    protected $guarded = ['id'];
    public $table = 'lo_logs';
    public $casts = ['category' => EventType::class];
}
