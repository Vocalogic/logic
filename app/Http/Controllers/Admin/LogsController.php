<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\LogSeverity;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AppLog;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class LogsController extends Controller
{

    /**
     * Shows list of log entries for a model
     * @param string $model
     * @param int $id
     * @param int|null $logseverity
     * @return View
     */
    public function show(string $model, int $id): View
    {
        $logs = AppLog::query()
            ->where('type', "App\\Models\\{$model}")
            ->where('type_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.logs.show', ['logs' => $logs]);
    }
}
