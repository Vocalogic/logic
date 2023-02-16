<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Core\LogSeverity;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AppLog;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use App\Operations\Core\LogOperation;

class LogsController extends Controller
{

    /**
     * Shows list of log entries for a model
     * @param string $model
     * @param int    $id
     * @return View
     */
    public function show(string $model, int $id, LogOperation $service): View
    {
        return view('admin.logs.show', ['logs' => $service->getModelLogs($model, $id)]);
    }

    /**
     * Extended log view
     */
    public function extendedView(string $model, int $id, LogOperation $service): View
    {
        return view('admin.logs.extended', [
          'logs' => AppLog::all(),
          'modelName' => ucfirst($model),
          'entity' => $service->loadModel($model, $id)
        ]);
    }
}
