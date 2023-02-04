<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer     $user_id
 * @property integer     $account_id
 * @property string      $type
 * @property integer     $type_id
 * @property integer     $log_level
 * @property string     $log
 * @property string     $detail
 */
class AppLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
