<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileCategory;
use App\Models\LeadType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FileCategoryController extends Controller
{
    /**
     * Show all lead types
     * @return View
     */
    public function index(): View
    {
        return view('admin.file_categories.index');
    }

    /**
     * Show create form
     * @return View
     */
    public function create(): View
    {
        return view('admin.file_categories.create')->with('category', new FileCategory);
    }

    /**
     * Show Edit Form
     * @param FileCategory $fileCategory
     * @return View
     */
    public function show(FileCategory $fileCategory): View
    {
        return view('admin.file_categories.create')->with('category', $fileCategory);
    }

    /**
     * Store a new File Category
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required', 'type' => 'required']);
        (new FileCategory)->create([
            'name'           => $request->name,
            'type'           => $request->type,
            'default_public' => $request->default_public ? 1 : 0
        ]);
        return redirect()->to("/admin/file_categories");
    }

    /**
     * Update File Category
     * @param FileCategory $fileCategory
     * @param Request      $request
     * @return RedirectResponse
     */
    public function update(FileCategory $fileCategory, Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required', 'type' => 'required']);
        $fileCategory->update([
            'name'           => $request->name,
            'type'           => $request->type,
            'default_public' => $request->default_public ? 1 : 0
        ]);
        return redirect()->to("/admin/file_categories");
    }

    /**
     * Delete a lead type
     * @param FileCategory $fileCategory
     * @return string[]
     */
    public function destroy(FileCategory $fileCategory): array
    {
        // Move all files out of a category first and put them into something.


        return ['callback' => "redirect:/admin/file_categories"];

    }
}
