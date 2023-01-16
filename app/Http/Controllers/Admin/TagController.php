<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillCategory;
use App\Models\Tag;
use App\Models\TagCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class TagController extends Controller
{
    /**
     * Show all tags in a given category
     * @param BillCategory $category
     * @param TagCategory  $tagCategory
     * @return View
     */
    public function index(BillCategory $category, TagCategory $tagCategory): View
    {
        return view('admin.tag_categories.tags.index')->with(['category' => $category, 'cat' => $tagCategory]);
    }

    /**
     * Create a new tag
     * @param BillCategory $category
     * @param TagCategory  $tagCategory
     * @return View
     */
    public function create(BillCategory $category, TagCategory $tagCategory): View
    {
        return view('admin.tag_categories.tags.create')->with([
            'category' => $category,
            'cat'      => $tagCategory,
            'tag'      => new Tag
        ]);
    }

    /**
     * Show Tag Editor
     * @param BillCategory $category
     * @param TagCategory  $tagCategory
     * @param Tag          $tag
     * @return View
     */
    public function show(BillCategory $category, TagCategory $tagCategory, Tag $tag): View
    {
        return view('admin.tag_categories.tags.create')->with(['tag' => $tag, 'cat' => $tagCategory, 'category' => $category]);
    }

    /**
     * Create new tag
     * @param BillCategory $category
     * @param TagCategory  $tagCategory
     * @param Request      $request
     * @return RedirectResponse
     */
    public function store(BillCategory $category, TagCategory $tagCategory, Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required'
        ]);
        $request->merge(['tag_category_id' => $tagCategory->id]);
        (new Tag)->create($request->all());
        return redirect()->to("/admin/categories/$category->id/tag_categories/$tagCategory->id/tags")
            ->with('message', 'Tag created successfully.');
    }

    /**
     * Update a tag
     * @param BillCategory $category
     * @param TagCategory  $tagCategory
     * @param Tag          $tag
     * @param Request      $request
     * @return RedirectResponse
     */
    public function update(BillCategory $category, TagCategory $tagCategory, Tag $tag, Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required'
        ]);
        $tag->update($request->all());
        return redirect()->to("/admin/categories/$category->id/tag_categories/$tagCategory->id/tags")
            ->with('message', 'Tag updated successfully.');
    }

    /**
     * Safely remove a tag
     * @param BillCategory $category
     * @param TagCategory  $tagCategory
     * @param Tag          $tag
     * @return array
     */
    public function destroy(BillCategory $category, TagCategory $tagCategory, Tag $tag) : array
    {
        $tag->remove();
        return ['callback' => "redirect:/admin/categories/$category->id/tag_categories/$tagCategory->id/tags"];
    }
}
