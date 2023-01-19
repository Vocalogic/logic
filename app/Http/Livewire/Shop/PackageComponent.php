<?php

namespace App\Http\Livewire\Shop;

use App\Enums\Core\CommKey;
use App\Exceptions\LogicException;
use App\Models\BillItem;
use App\Models\PackageBuild;
use App\Models\PackageSection;
use App\Models\PackageSectionQuestion;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * This component will handle all the steps and information gathering for
 * building a cart based on the input given by the customer.
 */
class PackageComponent extends Component
{
    public PackageBuild $build;
    public string       $errorMessage = '';
    public array        $steps        = [];
    public int          $step         = 0;
    public array        $questions    = [];
    public array        $answers      = [];


    /**
     * Buildout Initializations
     * @return void
     */
    public function mount(): void
    {
        $this->buildAnswers(); // Init our answer array.
        $this->createSteps();  // Init all our steps
        $this->renderStep();
    }

    /**
     * Renter content for the component.
     * @return View
     * @throws LogicException
     */
    public function render(): View
    {
        $this->buildCart();
        $this->emit('cartUpdated');
        session([CommKey::LocalPackageAnswerSession->value => json_encode($this->answers)]);
        return view('shop.package.component');
    }

    private function createSteps()
    {
        foreach ($this->build->sections as $section)
        {
            $this->steps[] = [
                'id'          => $section->id,
                'icon'        => 'qvzrpodt',
                'complete'    => false,
                'title'       => $section->name,
                'description' => $section->description
            ];
        }
    }

    public function setStep(int $step)
    {
        $this->step = $step;
        $this->renderStep();
    }

    public function nextStep(): void
    {
        $this->step++;
        $this->renderStep();
    }

    private function renderStep()
    {
        $step = $this->steps[$this->step];
        $section = PackageSection::find($step['id']);
        $this->questions = $section->questions->toArray();
    }

    /**
     * Are we showing this question?
     * @param array $question
     * @return bool
     */
    public function shouldRender(array $question): bool
    {
        $q = PackageSectionQuestion::find($question['id']);
        $show = $q->default_show; // Start our boolean here.
        // Unless
        if ($q->unless_question_id)
        {
            $invert = false;
            $answer = $this->answers["q_$q->unless_question_id"];
            switch($q->question_equates)
            {
                case 'equals' : $invert = ($answer == $q->question_equates_to);
                break;
                case 'greater' : $invert = ($answer > $q->question_equates_to);
                break;
                case 'less' : $invert = ($answer < $q->question_equates_to);
                break;
                case 'notequals' : $invert = ($answer != $q->question_equates_to);
                break;
                case 'exists' : $invert = strlen($answer) > 0;
                break;
                case 'notexists' : $invert = strlen($answer) == 0;
                break;
            }
            if ($invert)
            {
                // we met a match from above, so we need to invert our show
                $show = !$show;
            }
        }
        return $show;
    }

    /**
     * Build out indexes for answers based on every question in the build
     * @return void
     */
    private function buildAnswers(): void
    {
        // We're just going to build out every single question id with an empty value.
        foreach ($this->build->sections as $section)
        {
            foreach ($section->questions as $question)
            {
                $this->answers["q_$question->id"] = '';
            }
        }
    }

    /**
     * This method will completely destroy the cart, and rebuild based on all
     * information given at this point in time.
     * @return void
     * @throws LogicException
     */
    public function buildCart() : void
    {
        $cart = cart();
        $cart->removeAll(); // Remove all items in cart first.
        // Now we go through all questions and validate answers against the logic table.
        foreach ($this->build->sections as $section)
        {
            foreach ($section->questions as $question)
            {
                foreach($question->logics as $logic)
                {
                    $answer = $this->answers["q_$question->id"];
                    $passes = false;
                    switch($logic->answer_equates)
                    {
                        case 'equals' : $passes = ($answer == $logic->answer);
                            break;
                        case 'greater' : $passes = ($answer > $logic->answer);
                            break;
                        case 'less' : $passes = ($answer < $logic->answer);
                            break;
                        case 'notequals' : $passes = ($answer != $logic->answer);
                            break;
                        case 'exists' : $passes = strlen($answer) > 0;
                            break;
                        case 'notexists' : $passes = strlen($answer) == 0;
                            break;
                    }
                    if ($passes)
                    {
                        $qty = $logic->qty_from_answer ? $answer : $logic->qty;
                        if (!is_numeric($qty)) $qty = 1;
                        if ($logic->add_item_id)
                        {
                            try
                            {
                                $cart->addItem(BillItem::find($logic->add_item_id), $qty);
                            } catch (LogicException)
                            {
                                // Don't kill LW based on an inventory error
                            }
                        } // if adding bill item
                        if ($logic->add_addon_id)
                        {
                            // We are adding an addon to an existing bill item that is in our previous logic.
                            // Since our cart takes in the uid and not the actual bill item we will need to
                            // get the uid based on the item that this addon represents
                            $uid = $cart->getUidByItem($logic->addon->addon->item);
                            if ($uid)
                            {
                                $cart->applyAddon($uid, $logic->addon, $qty);
                            }
                        }
                    } // if passes
                } // fe logic

                if ($question->type == 'product')
                {
                    // We should add each item to the cart based on the answers.
                    if (!is_array($this->answers["q_$question->id"])) continue; // No indexes were built from the form.
                    foreach($this->answers["q_$question->id"] as $key => $qty)
                    {
                        $x = explode("i_", $key);
                        $id = $x[1];
                        if (!is_numeric($qty)) $qty = 1;
                        try
                        {
                            $cart->addItem(BillItem::find($id), $qty);
                        } catch(LogicException)
                        {
                            // Don't kill lw
                        }
                    }
                }
            } //fe question
        } // fe section

    } //fn



}
