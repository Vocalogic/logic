<?php

namespace App\Operations\API\OpenAI;

use App\Exceptions\LogicException;
use App\Models\BillItem;
use App\Models\Project;
use OpenAI\Laravel\Facades\OpenAI as OpenAIAlias;

/**
 * This class will implement the basic API functions of ChatGPT to
 * help assist with various areas in the application; namely the
 * Product Catalog and helping generate descriptions and other helpers.
 */
class OpenAI
{
    public string $apiKey;
    public int    $maxWords    = 500;
    public float  $temperature = 0.9;


    /**
     * Set our OpenAI Key from Settings
     * @throws LogicException
     */
    public function __construct()
    {
        $this->apiKey = setting('quotes.openai');
        config(['openai.api_key' => $this->apiKey]);
        if (!$this->apiKey) throw new LogicException("OpenAI API Key not configured.");
    }

    /**
     * Attempt to get a response to a query from GPT
     * @param string $query
     * @param string $model
     * @return string
     * @throws LogicException
     */
    public function getResponse(string $query, string $model = 'text-davinci-003'): string
    {
        $result = OpenAIAlias::completions()->create([
            'model'       => $model,
            'prompt'      => $query,
            'max_tokens'  => $this->maxWords,
            'temperature' => $this->temperature
        ]);
        info(print_r($result, true));
        if (isset($result['choices'][0]['text']))
        {
            return trim($result['choices'][0]['text']);
        }
        throw new LogicException("Unable to get Response from ChatGPT. Please try again or rephrase.");
    }

    /**
     * This method handles getting the various descriptions for a billitem.
     * Modes are:
     * invoice (description)
     * features (feature highlights),
     * faq (frequently asked questions)
     * @param BillItem $item
     * @param string   $mode
     * @param int      $sentences
     * @return string
     * @throws LogicException
     */
    public function byItem(BillItem $item, string $mode = 'invoice', int $sentences = 3): string
    {
        // Step 1: Build our prompts based on what we are actually looking for.
        $query = match ($mode)
        {
            'invoice' => sprintf("Create an invoice line item description, without price, replacing the word 'this' with 'the', limited to %d sentences for the following product: %s",
                $sentences, $item->name),
            'faq' => sprintf("Create a list of %d frequently asked questions for the product: '%s' returned as a json array with the child objects having only the keys 'question' and 'answer' accordingly.",
                $sentences, $item->name),
            'marketing' => sprintf("Create a product marketing description, formatted in html, for the following product: '%s' limited to %d paragraphs.",
                $item->name, $sentences),
            'features' => sprintf("Create a list of %d features without using numbers when defining each feature, separated by a carriage return, for the following product: '%s'",
                $sentences, $item->name),
            default => ''
        };
        return $this->getResponse($query);
    }

    /**
     * Create a statement of work based on project (beta needs work)
     * @param Project $project
     * @return string
     * @throws LogicException
     */
    public function byProject(Project $project)
    {
        $query = "Given the following information, create a statement of work, in HTML format, for a project, including dates, action items (categories), and cost. The criteria for this statement of work will be prefixed with ** to delineate each of the different items. ";
        $criteria = [];
        $criteria[] = "** Project Estimated Cost: $" . moneyFormat($project->totalMax);
        $criteria[] = "** Client Name: " . $project->lead ? $project->lead->company : $project->account->name;
        $criteria[] = "** Projected Start Date: " . $project->start_date;
        $criteria[] = "** Projected End Date: " . $project->end_date;
        $criteria[] = "** Description of Work: " . $project->summary;
        foreach ($project->categories as $category)
        {
            $criteria[] = "** Milestone: $category->name includes the following tasks: ";
            $tasks = [];
            foreach ($category->tasks as $task)
            {
                $tasks[] = $task->name;
                $criteria[] = implode(", ", $tasks);
            }
        }
        $query .= implode("\n", $criteria);
        return $this->getResponse($query);
    }

}
