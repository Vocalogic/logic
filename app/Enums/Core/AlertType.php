<?php

namespace App\Enums\Core;

enum AlertType: string
{
    case Ok = 'success';
    case Warning = 'warning';
    case Danger = 'danger';
    case Info = 'info';
}
