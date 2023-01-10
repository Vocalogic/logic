<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageBuild;
use App\Models\PackageSection;
use App\Models\PackageSectionQuestion;
use App\Models\PackageSectionQuestionLogic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionLogicController extends Controller
{

    /**
     * Show logic editor for a question
     * @param PackageBuild           $packageBuild
     * @param PackageSection         $section
     * @param PackageSectionQuestion $question
     * @return View
     */
    public function index(PackageBuild $packageBuild, PackageSection $section, PackageSectionQuestion $question): View
    {
        return view('admin.package_builds.sections.questions.logic.index', [
            'build'    => $packageBuild,
            'section'  => $section,
            'question' => $question
        ]);
    }

    /**
     * Create new Question Logic
     * @param PackageBuild           $packageBuild
     * @param PackageSection         $section
     * @param PackageSectionQuestion $question
     * @return View
     */
    public function create(PackageBuild $packageBuild, PackageSection $section, PackageSectionQuestion $question): View
    {
        return view('admin.package_builds.sections.questions.logic.create', [
            'build'    => $packageBuild,
            'section'  => $section,
            'question' => $question,
            'logic'    => new PackageSectionQuestionLogic
        ]);
    }

    /**
     * Show Question Logic
     * @param PackageBuild                $packageBuild
     * @param PackageSection              $section
     * @param PackageSectionQuestion      $question
     * @param PackageSectionQuestionLogic $logic
     * @return View
     */
    public function show(
        PackageBuild $packageBuild,
        PackageSection $section,
        PackageSectionQuestion $question,
        PackageSectionQuestionLogic $logic
    ): View {
        return view('admin.package_builds.sections.questions.logic.create', [
            'build'    => $packageBuild,
            'section'  => $section,
            'question' => $question,
            'logic'    => $logic
        ]);
    }

    /**
     * Save new Logic Parameter
     * @param PackageBuild           $packageBuild
     * @param PackageSection         $section
     * @param PackageSectionQuestion $question
     * @param Request                $request
     * @return RedirectResponse
     */
    public function store(
        PackageBuild $packageBuild,
        PackageSection $section,
        PackageSectionQuestion $question,
        Request $request
    ): RedirectResponse {
        $request->validate(['add_item_id' => 'required', 'answer' => 'required']);
        $question->logics()->create([
            'answer_equates'  => $request->answer_equates,
            'answer'          => $request->answer,
            'add_item_id'     => $request->add_item_id,
            'qty_from_answer' => (bool)$request->qty_from_answer,
            'qty'             => $request->qty
        ]);
        return redirect()->back()->with('message', "New Logic Operation Added");
    }

    /**
     * Update  Logic Parameter
     * @param PackageBuild                $packageBuild
     * @param PackageSection              $section
     * @param PackageSectionQuestion      $question
     * @param PackageSectionQuestionLogic $logic
     * @param Request                     $request
     * @return RedirectResponse
     */
    public function update(
        PackageBuild $packageBuild,
        PackageSection $section,
        PackageSectionQuestion $question,
        PackageSectionQuestionLogic $logic,
        Request $request
    ): RedirectResponse {
        $request->validate(['add_item_id' => 'required', 'answer' => 'required']);
        $logic->update([
            'answer_equates'  => $request->answer_equates,
            'answer'          => $request->answer,
            'add_item_id'     => $request->add_item_id,
            'qty_from_answer' => (bool)$request->qty_from_answer,
            'qty'             => $request->qty
        ]);
        return redirect()->back()->with('message', "New Logic Operation Added");
    }

    /**
     * Remove Logic Operation
     * @param PackageBuild                $packageBuild
     * @param PackageSection              $section
     * @param PackageSectionQuestion      $question
     * @param PackageSectionQuestionLogic $logic
     * @return array
     */
    public function destroy(
        PackageBuild $packageBuild,
        PackageSection $section,
        PackageSectionQuestion $question,
        PackageSectionQuestionLogic $logic
    ): array {
        $logic->delete();
        return ['callback' => 'reload'];
    }
}
