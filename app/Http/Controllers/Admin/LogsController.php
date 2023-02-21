<?php

namespace App\Http\Controllers\Admin;

use App\Models\AppLog;
use App\Http\Controllers\Controller;
use App\Operations\Core\LogOperation;
use Illuminate\Contracts\View\View;

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
          'modelName' => ucfirst($model),
          'entity' => $service->loadModel($model, $id)
        ]);
    }
}
