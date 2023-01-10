<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageBuild;
use App\Models\PackageSection;
use App\Models\PackageSectionQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageSectionQuestionController extends Controller
{
    /**
     * Show all questions in a section
     * @param PackageBuild   $packageBuild
     * @param PackageSection $section
     * @return View
     */
    public function index(PackageBuild $packageBuild, PackageSection $section): View
    {
        return view('admin.package_builds.sections.questions.index', ['build' => $packageBuild, 'section' => $section]);
    }

    /**
     * Show Create modal for a question
     * @param PackageBuild   $packageBuild
     * @param PackageSection $section
     * @return View
     */
    public function create(PackageBuild $packageBuild, PackageSection $section): View
    {
        return view('admin.package_builds.sections.questions.create', [
            'build'    => $packageBuild,
            'section'  => $section,
            'question' => new PackageSectionQuestion
        ]);
    }

    /**
     * Show question editor modal.
     * @param PackageBuild           $packageBuild
     * @param PackageSection         $section
     * @param PackageSectionQuestion $question
     * @return View
     */
    public function show(PackageBuild $packageBuild, PackageSection $section, PackageSectionQuestion $question): View
    {
        return view('admin.package_builds.sections.questions.create', [
            'build'    => $packageBuild,
            'section'  => $section,
            'question' => $question
        ]);
    }

    /**
     * Store new question for a section.
     * @param PackageBuild   $packageBuild
     * @param PackageSection $section
     * @param Request        $request
     * @return RedirectResponse
     */
    public function store(PackageBuild $packageBuild, PackageSection $section, Request $request): RedirectResponse
    {
        $request->validate(['question' => 'required', 'type' => 'required']);
        $section->questions()->create([
            'question'            => $request->question,
            'type'                => $request->type,
            'is_numeric'          => (bool)$request->is_numeric,
            'qty_from_answer_id'  => $request->qty_from_answer_id,
            'default_show'        => (bool)$request->default_show,
            'unless_question_id'  => $request->unless_question_id,
            'question_equates'    => $request->question_equates,
            'question_equates_to' => $request->question_equates_to
        ]);
        return redirect()->back()->with('message', "Question Created Successfully");
    }

    /**
     * Update question for a section.
     * @param PackageBuild           $packageBuild
     * @param PackageSection         $section
     * @param PackageSectionQuestion $question
     * @param Request                $request
     * @return RedirectResponse
     */
    public function update(
        PackageBuild $packageBuild,
        PackageSection $section,
        PackageSectionQuestion $question,
        Request $request
    ): RedirectResponse {
        $request->validate(['question' => 'required', 'type' => 'required']);
        $question->update([
            'question'            => $request->question,
            'type'                => $request->type,
            'is_numeric'          => (bool)$request->is_numeric,
            'qty_from_answer_id'  => $request->qty_from_answer_id,
            'default_show'        => (bool)$request->default_show,
            'unless_question_id'  => $request->unless_question_id,
            'question_equates'    => $request->question_equates,
            'question_equates_to' => $request->question_equates_to
        ]);
        return redirect()->back()->with('message', "Question Updated Successfully");
    }


}
