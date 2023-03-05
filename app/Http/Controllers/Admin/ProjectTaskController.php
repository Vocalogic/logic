<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectTaskController extends Controller
{
    /**
     * Create a new Task for a Project
     * @param Project $project
     * @param Request $request
     * @return View
     */
    public function create(Project $project, Request $request): View
    {
        $category = ProjectCategory::find($request->category);
        return view('admin.projects.tasks.create', ['project' => $project, 'category' => $category]);
    }

    /**
     * Show task editor.
     * @param Project     $project
     * @param ProjectTask $task
     * @return View
     */
    public function show(Project $project, ProjectTask $task): View
    {
        return view('admin.projects.tasks.show', ['task' => $task, 'project' => $project]);
    }

    /**
     * Create a new task inside a project.
     * @param Project $project
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Project $project, Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required']);
        $category = ProjectCategory::find($request->category);
        $task = $category->tasks()->create([
            'name'             => $request->name,
            'project_id'       => $project->id,
            'task_hourly_rate' => $category->category_hourly_rate
        ]);
        return redirect()->to("/admin/projects/$project->id/tasks/$task->id?editdesc=true");
    }

    /**
     * Update a task
     * @param Project     $project
     * @param ProjectTask $task
     * @param Request     $request
     * @return RedirectResponse
     */
    public function update(Project $project, ProjectTask $task, Request $request): RedirectResponse
    {
        $moneyLines = ['static_price', 'task_hourly_rate'];
        $numericals = ['est_hours_min', 'est_hours_max'];
        foreach ($request->all() as $key => $value)
        {
            if (in_array($key, $moneyLines))
            {
                $request->merge([$key => convertMoney($value)]);
            }
            if (in_array($key, $numericals))
            {
                $request->merge([$key => onlyNumbers($value)]);
            }
        }
        $task->update($request->all());
        return redirect()->to("/admin/projects/$project->id/tasks/$task->id")->with('message', "Task Updated");
    }

    /**
     * Remove a task from the project.
     * @param Project     $project
     * @param ProjectTask $task
     * @return string[]
     */
    public function destroy(Project $project, ProjectTask $task): array
    {
        $task->delete();
        session()->flash('message', "Task removed from Project");
        return ['callback' => "redirect:/admin/projects/$project->id"];
    }

    /**
     * Mark a task completed.
     * @param Project     $project
     * @param ProjectTask $task
     * @return string[]
     */
    public function complete(Project $project, ProjectTask $task) : array
    {
        $task->update(['completed' => true]);
        session()->flash('message', "Task #$task->id marked completed.");
        return ['callback' => "redirect:/admin/projects/$project->id/tasks/$task->id"];
    }

}
