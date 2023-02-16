<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Throwable;

class LogsController extends Controller
{

    /**
     * Shows list of log entries for a model
     * @param string $model
     * @param int    $id
     * @return View
     */
    public function show(string $model, int $id): View
    {
        $model = ucfirst($model);
        $class = "\\App\\Models\\$model";
        try
        {
            $entity = $class::find($id);
            if (isset($entity))
            {
                $logs = $entity->getLogs();
            }
            else $logs = collect(); // empty collection if no model/record found
        } catch (Throwable)
        {
            $logs = collect();
        }
        return view('admin.logs.show', ['logs' => $logs]);
    }
}
