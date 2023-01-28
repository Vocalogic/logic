<?php

namespace App\Http\Controllers\Admin;

use App\Models\PackageBuild;
use App\Models\PackageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class PackageSectionController
{
    /**
     * Show all sections of a package
     * @param PackageBuild $packageBuild
     * @return View
     */
    public function index(PackageBuild $packageBuild): View
    {
        return view('admin.package_builds.sections.index', ['build' => $packageBuild]);
    }

    /**
     * Show modal for creating a new section.
     * @param PackageBuild $packageBuild
     * @return View
     */
    public function create(PackageBuild $packageBuild): View
    {
        return view('admin.package_builds.sections.create',
            ['build' => $packageBuild, 'section' => new PackageSection]);
    }

    /**
     * Show Section Editor
     * @param PackageBuild   $packageBuild
     * @param PackageSection $section
     * @return View
     */
    public function show(PackageBuild $packageBuild, PackageSection $section): View
    {
        return view('admin.package_builds.sections.create', ['build' => $packageBuild, 'section' => $section]);
    }

    /**
     * Store a new Section
     * @param PackageBuild $packageBuild
     * @param Request      $request
     * @return RedirectResponse
     */
    public function store(PackageBuild $packageBuild, Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required'
        ]);
        $packageBuild->sections()->create([
            'name'                => $request->name,
            'default_show'        => (bool)$request->default_show,
            'unless_question_id'  => $request->unless_question_id,
            'question_equates'    => $request->question_equates,
            'question_equates_to' => $request->question_equates_to,
            'description'         => $request->description
        ]);
        return redirect()->back()->with('message', "Section Created Successfully");
    }

    /**
     * Update Section
     * @param PackageBuild   $packageBuild
     * @param PackageSection $section
     * @param Request        $request
     * @return RedirectResponse
     */
    public function update(PackageBuild $packageBuild, PackageSection $section, Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required'
        ]);
        $section->update([
            'name'                => $request->name,
            'default_show'        => (bool)$request->default_show,
            'unless_question_id'  => $request->unless_question_id,
            'question_equates'    => $request->question_equates,
            'question_equates_to' => $request->question_equates_to,
            'description'         => $request->description
        ]);
        return redirect()->back()->with('message', "Section Updated Successfully");
    }

    /**
     * Remove an entire section.
     * @param PackageBuild   $packageBuild
     * @param PackageSection $section
     * @return string[]
     */
    public function destroy(PackageBuild $packageBuild, PackageSection $section) : array
    {
        foreach($section->questions as $question)
        {
            $question->logics()->delete();
            $question->options()->delete();
            $question->delete();
        }
        $section->delete();
        session()->flash('message', 'Section removed');
        return ['callback' => 'reload'];
    }
}
