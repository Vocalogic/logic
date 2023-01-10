<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $body
 */
class EmailTemplate extends Model
{
    protected $guarded = ['id'];

    /**
     * Any templates with placeholder in the body, will be replaced
     * by a template replacement if found
     * @return void
     */
    static public function placeholders(): void
    {
        foreach (self::where('body', 'like', '%placeholder%')->get() as $template)
        {
            try
            {
                $name = str_replace(".", "-", $template->ident);
                $data = view("template_holders." . $name)->render();
                $template->update(['body' => $data]);
            } catch (Exception $e)
            {
                // None found
            }
        }
    }
}
