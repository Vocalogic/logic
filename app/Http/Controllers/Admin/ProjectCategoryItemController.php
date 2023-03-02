<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillItem;
use App\Models\Project;
use App\Models\ProjectCategory;
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

}
