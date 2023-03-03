<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillItem;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectCategoryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectCategoryItemController extends Controller
{
    /**
     * Show create modal for adding a new product
     * @param Project         $project
     * @param ProjectCategory $category
     * @return View
     */
    public function create(Project $project, ProjectCategory $category): View
    {
        return view('admin.projects.categories.billables.create', ['project' => $project, 'category' => $category]);
    }

    /**
     * Show create modal for adding a new product
     * @param Project             $project
     * @param ProjectCategory     $category
     * @param ProjectCategoryItem $item
     * @return View
     */
    public function show(Project $project, ProjectCategory $category, ProjectCategoryItem $item): View
    {
        return view('admin.projects.categories.billables.edit',
            ['project' => $project, 'category' => $category, 'item' => $item]);
    }

    /**
     * Update a billable item
     * @param Project             $project
     * @param ProjectCategory     $category
     * @param ProjectCategoryItem $item
     * @param Request             $request
     * @return RedirectResponse
     */
    public function update(Project $project, ProjectCategory $category, ProjectCategoryItem $item, Request $request): RedirectResponse
    {
        $request->validate(['price' => 'required']);
        $moneyLines = ['price', 'expense'];
        foreach ($request->all() as $key => $value)
        {
            if (in_array($key, $moneyLines))
            {
                $request->merge([$key => convertMoney($value)]);
            }
        }
        $item->update($request->all());
        return redirect()->back()->with('message', "$item->name updated.");
    }

    /**
     * Create a custom billable item.
     * @param Project         $project
     * @param ProjectCategory $category
     * @param Request         $request
     * @return RedirectResponse
     */
    public function store(Project $project, ProjectCategory $category, Request $request): RedirectResponse
    {
        $request->validate(['custom_item' => 'required', 'price' => "required"]);
        $category->items()->create([
            'code'        => "CUSTOM",
            'name'        => $request->custom_item,
            'description' => '',
            'price'       => convertMoney($request->price),
            'qty'         => $request->qty ?: 1,
            'user_id'     => user()->id,
            'expense'     => 0
        ]);
        return redirect()->back()->with('message', $request->custom_item . " Added to $category->name");
    }

    /**
     * Add an item to a category.
     * @param Project         $project
     * @param ProjectCategory $category
     * @param BillItem        $item
     * @return RedirectResponse
     */
    public function addItem(Project $project, ProjectCategory $category, BillItem $item): RedirectResponse
    {
        $category->items()->create([
            'bill_item_id' => $item->id,
            'code'         => $item->code,
            'name'         => $item->name,
            'description'  => $item->description,
            'price'        => $item->nrc,
            'qty'          => 1,
            'user_id'      => user()->id,
            'expense'      => 0,
        ]);
        return redirect()->back()->with('message', $item->name . " Added to $category->name");
    }

    /**
     * Remove a billable item.
     * @param Project             $project
     * @param ProjectCategory     $category
     * @param ProjectCategoryItem $item
     * @return string[]
     */
    public function destroy(Project $project, ProjectCategory $category, ProjectCategoryItem $item) : array
    {
        session()->flash('message', $item->name . " deleted from $category->name");
        $item->delete();
        return ['callback' => 'reload'];
    }

}
