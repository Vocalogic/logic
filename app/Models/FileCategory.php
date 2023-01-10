<?php

namespace App\Models;

use App\Enums\Core\AccountFileType;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $id
 */
class FileCategory extends Model
{
    protected $casts = ['type' => AccountFileType::class];
    protected $guarded = ['id'];




}
