<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


class ProjectCategoryController extends Controller
{

    /**
     * Create a new project category
     * @param Project $project
     * @return View
     */
    public function create(Project $project): View
    {
        return view('admin.projects.categories.create', ['project' => $project, 'category' => new ProjectCategory]);
    }

    /**
     * Show category editor
     * @param Project         $project
     * @param ProjectCategory $category
     * @return View
     */
    public function show(Project $project, ProjectCategory $category): View
    {
        return view('admin.projects.categories.show', ['project' => $project, 'category' => $category]);
    }

    /**
     * Store new category
     * @param Project $project
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Project $project, Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required']);
        $project->categories()->create([
            'name'                 => $request->name,
            'description'          => $request->description,
            'category_hourly_rate' => $project->project_hourly_rate
        ]);
        return redirect()->to("/admin/projects/$project->id")->with('message', "Category Created");
    }

    /**
     * Update Category Settings
     * @param Project         $project
     * @param ProjectCategory $category
     * @param Request         $request
     * @return RedirectResponse
     */
    public function update(Project $project, ProjectCategory $category, Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required']);
        $moneyLines = ['static_price', 'category_hourly_rate'];
        foreach ($request->all() as $key => $value)
        {
            if (in_array($key, $moneyLines))
            {
                $request->merge([$key => convertMoney($value)]);
            }
        }
        $category->update([
            'name'                 => $request->name,
            'description'          => $request->description,
            'static_price'         => $request->static_price,
            'bill_method'          => $request->bill_method,
            'category_hourly_rate' => $request->category_hourly_rate
        ]);
        return redirect()->to("/admin/projects/$project->id/categories/$category->id")
            ->with('message', "Category Updated");
    }
}
