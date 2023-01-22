<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\LogicException;
use App\Http\Controllers\Controller;
use App\Models\PackageBuild;
use App\Models\PackageSection;
use App\Models\PackageSectionQuestion;
use App\Models\PackageSectionQuestionLogic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

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
     * @throws LogicException
     */
    public function store(
        PackageBuild $packageBuild,
        PackageSection $section,
        PackageSectionQuestion $question,
        Request $request
    ): RedirectResponse {
        $request->validate(['answer' => 'required']);
        if ($request->add_addon_id && $request->add_item_id)
        {
            throw new LogicException("You can only specify an item or an addon in each entry.");
        }
        if (!$request->add_addon_id && !$request->add_item_id)
        {
            throw new LogicException("You must add either an item or an addon.");
        }
        if ($request->answer_equates == 'between')
        {
            $x = explode(",", $request->answer);
            if (count($x) < 2)
            {
                throw new LogicException("When setting between two values, you must specify two values separated by a comma. ex. 1,20");
            }
        }
        $question->logics()->create([
            'answer_equates'  => $request->answer_equates,
            'answer'          => $request->answer,
            'add_item_id'     => $request->add_item_id,
            'add_addon_id'    => $request->add_addon_id,
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
     * @throws LogicException
     */
    public function update(
        PackageBuild $packageBuild,
        PackageSection $section,
        PackageSectionQuestion $question,
        PackageSectionQuestionLogic $logic,
        Request $request
    ): RedirectResponse {
        $request->validate(['answer' => 'required']);
        if ($request->add_addon_id && $request->add_item_id)
        {
            throw new LogicException("You can only specify an item or an addon in each entry.");
        }
        if (!$request->add_addon_id && !$request->add_item_id)
        {
            throw new LogicException("You must add either an item or an addon.");
        }
        if ($request->answer_equates == 'between')
        {
            $x = explode(",", $request->answer);
            if (count($x) < 2)
            {
                throw new LogicException("When setting between two values, you must specify two values separated by a comma. ex. 1,20");
            }
        }
        $logic->update([
            'answer_equates'  => $request->answer_equates,
            'answer'          => $request->answer,
            'add_item_id'     => $request->add_item_id,
            'add_addon_id'    => $request->add_addon_id,
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
        // Make sure there are no addons that were associated to thie entry.
        foreach ($question->logics as $l)
        {
            if ($l->add_addon_id)
            {
                if ($l->addon->addon->item->id == $logic->addedItem->id)
                {
                    $l->delete(); // If we matched addeditem with the root addon item, delete this.
                }
            }
        }
        $logic->delete();
        return ['callback' => 'reload'];
    }
}
