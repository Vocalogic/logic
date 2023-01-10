<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillCategory;
use App\Models\Tag;
use App\Models\TagCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagCategoryController extends Controller
{
    /**
     * Show all tags
     * @param BillCategory $category
     * @return View
     */
    public function index(BillCategory $category): View
    {
        return view('admin.tag_categories.index', ['category' => $category]);
    }

    /**
     * Create a new tag
     * @param BillCategory $category
     * @return View
     */
    public function create(BillCategory $category): View
    {
        return view('admin.tag_categories.create')->with(['cat' => new TagCategory, 'category' => $category]);
    }

    /**
     * Show Tag Category Editor
     * @param BillCategory $category
     * @param TagCategory  $tagCategory
     * @return View
     */
    public function show(BillCategory $category, TagCategory $tagCategory): View
    {
        return view('admin.tag_categories.create')->with(['cat' => $tagCategory, 'category' => $category]);
    }

    /**
     * Create new tag
     * @param BillCategory $category
     * @param Request      $request
     * @return RedirectResponse
     */
    public function store(BillCategory $category, Request $request) : RedirectResponse
    {
        $request->validate([
            'name' => 'required'
        ]);
        $category->tagCategories()->create($request->all());
        return redirect()->to("/admin/categories/$category->id/tag_categories")->with('message', 'Tag catagory created successfully.');
    }

    /**
     * Update a tag
     * @param BillCategory $category
     * @param TagCategory  $tagCategory
     * @param Request      $request
     * @return RedirectResponse
     */
    public function update(BillCategory $category, TagCategory $tagCategory, Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required'
        ]);
        $tagCategory->update($request->all());
        return redirect()->to("/admin/categories/$category->id/tag_categories")->with('message', 'Tag category updated successfully.');
    }

    /**
     * Remove all tags and then the category.
     * @param BillCategory $category
     * @param TagCategory  $tagCategory
     * @return array
     */
    public function destroy(BillCategory $category, TagCategory $tagCategory) : array
    {
        foreach ($tagCategory->tags as $tag)
        {
            $tag->remove();
        }
        $tagCategory->delete();
        return ['callback' => "redirect:/admin/categories/$category->id/tag_categories"];
    }

}
