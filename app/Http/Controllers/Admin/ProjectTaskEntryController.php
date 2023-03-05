<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\ProjectTaskEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectTaskEntryController extends Controller
{
    /**
     * Create a new Task Entry
     * @param Project     $project
     * @param ProjectTask $task
     * @return View
     */
    public function create(Project $project, ProjectTask $task): View
    {
        return view('admin.projects.tasks.entries.create',
            ['project' => $project, 'task' => $task, 'entry' => new ProjectTaskEntry]);
    }

    /**
     * Edit a Task Entry
     * @param Project          $project
     * @param ProjectTask      $task
     * @param ProjectTaskEntry $entry
     * @return View
     */
    public function show(Project $project, ProjectTask $task, ProjectTaskEntry $entry): View
    {
        return view('admin.projects.tasks.entries.create',
            ['project' => $project, 'task' => $task, 'entry' => $entry]);
    }

    /**
     * Create a new time entry.
     * @param Project     $project
     * @param ProjectTask $task
     * @param Request     $request
     * @return RedirectResponse
     */
    public function store(Project $project, ProjectTask $task, Request $request): RedirectResponse
    {
        $request->validate(['hours' => 'required', 'description' => 'required']);
        $task->entries()->create([
            'user_id'     => user()->id,
            'description' => $request->description,
            'hours'       => $request->hours,
            'billable'    => (bool)$request->billable
        ]);
        return redirect()->back()->with('message', "Time Entry has been logged.");
    }

    /**
     * Create a new time entry.
     * @param Project          $project
     * @param ProjectTask      $task
     * @param ProjectTaskEntry $entry
     * @param Request          $request
     * @return RedirectResponse
     */
    public function update(
        Project $project,
        ProjectTask $task,
        ProjectTaskEntry $entry,
        Request $request
    ): RedirectResponse {
        $request->validate(['hours' => 'required', 'description' => 'required']);
        $entry->update([
            'user_id'     => user()->id,
            'description' => $request->description,
            'hours'       => $request->hours,
            'billable'    => (bool)$request->billable
        ]);
        return redirect()->back()->with('message', "Time Entry has been updated.");
    }

    /**
     * Remove a task entry.
     * @param Project          $project
     * @param ProjectTask      $task
     * @param ProjectTaskEntry $entry
     * @return string[]
     */
    public function destroy(Project $project, ProjectTask $task, ProjectTaskEntry $entry) : array
    {
        session()->flash('message', "Time entry removed");
        $entry->delete();
        return ['callback' => "redirect:/admin/projects/$project->id/tasks/$task->id"];
    }


}
