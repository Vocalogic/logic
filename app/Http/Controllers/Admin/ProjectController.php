<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Lead;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Show all projects
     * @return View
     */
    public function index(): View
    {
        return view('admin.projects.index');
    }

    /**
     * Show create modal for new project.
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        if ($request->lead_id)
        {
            $lead = Lead::find($request->lead_id);
            $account = new Account();
        }
        else
        {
            $account = Account::find($request->account_id);
            $lead = new Lead();
        }
        return view('admin.projects.create', ['account' => $account, 'lead' => $lead]);
    }

    /**
     * Store new project in the system.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required', 'description' => 'required']);
        $project = (new Project)->create([
            'name'        => $request->name,
            'description' => $request->description,
            'lead_id'     => $request->lead_id ?: null,
            'account_id'  => $request->account_id ?: null,
            'leader_id'   => user()->id,
            'hash'        => "PR-" . uniqid()
        ]);
        return redirect()->to("/admin/projects/$project->id")->with('message', "Project Created!");
    }

    /**
     * Show project overview
     * @param Project $project
     * @return View
     */
    public function show(Project $project): View
    {
        return view('admin.projects.show', ['project' => $project]);
    }

    /**
     * Update project settings.
     * @param Project $project
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Project $project, Request $request): RedirectResponse
    {
        $moneyLines = ['static_price', 'project_hourly_rate'];
        foreach ($request->all() as $key => $value)
        {
            if (in_array($key, $moneyLines))
            {
                $request->merge([$key => convertMoney($value)]);
            }
        }
        $project->update($request->all());
        return redirect()->to("/admin/projects/$project->id")->with('message', "Project Updated");
    }

    /**
     * Download the Project Summary and SOW
     * @param Project $project
     * @return mixed
     */
    public function download(Project $project): mixed
    {
        return $project->pdf();
    }
}
