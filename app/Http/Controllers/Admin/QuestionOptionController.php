<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageBuild;
use App\Models\PackageSection;
use App\Models\PackageSectionQuestion;
use App\Models\PackageSectionQuestionOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class QuestionOptionController extends Controller
{
    /**
     * Show option editor for a question for dropdowns and multi-inputs
     * @param PackageBuild           $packageBuild
     * @param PackageSection         $section
     * @param PackageSectionQuestion $question
     * @return View
     */
    public function index(PackageBuild $packageBuild, PackageSection $section, PackageSectionQuestion $question): View
    {
        return view('admin.package_builds.sections.questions.options.index', [
            'build'    => $packageBuild,
            'section'  => $section,
            'question' => $question
        ]);
    }

    /**
     * Show create Option Modal
     * @param PackageBuild           $packageBuild
     * @param PackageSection         $section
     * @param PackageSectionQuestion $question
     * @return View
     */
    public function create(PackageBuild $packageBuild, PackageSection $section, PackageSectionQuestion $question): View
    {
        return view('admin.package_builds.sections.questions.options.create', [
            'build'    => $packageBuild,
            'section'  => $section,
            'question' => $question,
            'option'   => new PackageSectionQuestionOption
        ]);
    }


    /**
     * Show Update Option Modal
     * @param PackageBuild                 $packageBuild
     * @param PackageSection               $section
     * @param PackageSectionQuestion       $question
     * @param PackageSectionQuestionOption $option
     * @return View
     */
    public function show(
        PackageBuild $packageBuild,
        PackageSection $section,
        PackageSectionQuestion $question,
        PackageSectionQuestionOption $option
    ): View {
        return view('admin.package_builds.sections.questions.options.create', [
            'build'    => $packageBuild,
            'section'  => $section,
            'question' => $question,
            'option'   => $option
        ]);
    }

    /**
     * Store new option for a question.
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
        $request->validate(['option' => 'required']);
        $question->options()->create([
            'option'      => $request->option,
            'description' => $request->description
        ]);
        return redirect()->back()->with('message', "Option Created Successfully");
    }

    /**
     * Update Option
     * @param PackageBuild                 $packageBuild
     * @param PackageSection               $section
     * @param PackageSectionQuestion       $question
     * @param PackageSectionQuestionOption $option
     * @param Request                      $request
     * @return RedirectResponse
     */
    public function update(
        PackageBuild $packageBuild,
        PackageSection $section,
        PackageSectionQuestion $question,
        PackageSectionQuestionOption $option,
        Request $request
    ): RedirectResponse {
        $request->validate(['option' => 'required']);
        $option->update([
            'option'      => $request->option,
            'description' => $request->description
        ]);
        return redirect()->back()->with('message', "Option updated");
    }

    /**
     * Remove Option
     * @param PackageBuild                 $packageBuild
     * @param PackageSection               $section
     * @param PackageSectionQuestion       $question
     * @param PackageSectionQuestionOption $option
     * @return string[]
     */
    public function destroy( PackageBuild $packageBuild,
        PackageSection $section,
        PackageSectionQuestion $question,
        PackageSectionQuestionOption $option) : array
    {
        session()->flash('message', "Option removed");
        $option->delete();
        return ['callback' => 'reload'];
    }

}
