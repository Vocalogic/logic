<?php

namespace App\Http\Livewire\Admin;

use App\Models\BillItem;
use Illuminate\View\View;
use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

class MarketingGeneratorComponent extends Component
{
    public string $apiKey;

    public BillItem $item;

    public int    $maxWords      = 500;
    public int    $maxSentences  = 4;
    public int    $maxParagraphs = 4;
    public int    $maxFaq        = 5;
    public string $productName;
    public string $marketing     = '';
    public string $faq           = '';
    public string $message       = '';

    public array $images = [];
    public string $quoteDescription = '';

    public $rules = [
        'quoteDescription' => '',
        'marketing'        => '',
        'productName'      => '',
        'faq'              => ''
    ];


    /**
     * Set Api Keys and stuff
     * @return void
     */
    public function mount(): void
    {
        $this->apiKey = setting('quotes.openai');
        $this->productName = $this->item->name;
    }

    /**
     * Generate a response from OpenAI
     * @param string $question
     * @return string
     */
    public function getResponse(string $question): string
    {
        config(['openai.api_key' => $this->apiKey]);

        $result = OpenAI::completions()->create([
            'model'       => 'text-davinci-003',
            'prompt'      => $question,
            'max_tokens'  => $this->maxWords,
            'temperature' => 0.9
        ]);
        if (isset($result['choices'][0]['text']))
        {
            return trim($result['choices'][0]['text']);
        }
        else return "Unable to Get Response";
    }

    /**
     * Generate an Invoice Line Item Description
     * @return void
     */
    public function generateInvoiceLine()
    {
        $answer = $this->getResponse("Create a product description, limited to {$this->maxSentences} sentences, describing a \"{$this->productName}\".");
        $this->quoteDescription = $answer;
    }

    /**
     * Generate Markdown for Marketing
     * @return void
     */
    public function generateMarketing()
    {
        $this->maxWords = 1000;
        $answer = $this->getResponse("Create a product description, limited to {$this->maxParagraphs} paragraphs, formatted in markdown, describing a \"{$this->productName}\".");
        $this->marketing = $answer;
    }

    /**
     * Generate Frequently Asked Questions
     * @return void
     */
    public function generateFaq()
    {
        $this->maxWords = 1000;
        $answer = $this->getResponse("Create a list of frequently asked questions with answers, limited to {$this->maxFaq}, listing each item separated by a carriage return and prefixing each question with 'Q' and each answer with 'A', about a \"{$this->productName}\".");
        $this->faq = $answer;
    }

    /**
     * Save Invoice Description
     * @return void
     */
    public function saveInvoiceLine(): void
    {
        $this->item->update(['description' => $this->quoteDescription]);
        $this->message = "Updated Product Description";
    }

    /**
     * Parse FAQ Responses
     * @return void
     */
    public function saveFaq(): void
    {
        $this->item->faqs()->delete();
        $x = explode("\n", $this->faq);
        foreach ($x as $line)
        {
            if (preg_match("/Q:/i", $line))
            {
                $question = $line;
            }
            if (preg_match("/A:/i", $line))
            {
                $this->item->faqs()->create([
                    'question' => str_replace("Q:", '', $question),
                    'answer'   => str_replace("A:", '', $line)
                ]);
            }
        }
        $this->message = "Updated Product's Frequently Asked Questions";
    }

    /**
     * Save Marketing Description
     * @return void
     */
    public function saveMarketing(): void
    {
        $this->item->update(['marketing_description' => $this->marketing]);
        $this->message = "Updated Marketing Information";
    }

    /**
     * Render Component
     * @return View
     */
    public function render(): View
    {
        return view('admin.bill_items.generator_component');
    }

}
