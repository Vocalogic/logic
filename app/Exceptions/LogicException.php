<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Throwable;

/**
 * Logic JSON Exception Handler
 */
class LogicException extends \Exception
{
    /**
     * Nexus Exception constructor.
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Handle an exception gracefully.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function render(Request $request)
    {
        if ($request->ajax())
        {
            $message = $this->getMessage();
            if (preg_match("/\:/", $message))
            {
                $msg = explode(":", $message);
                $title = $msg[0];
                $content = $msg[1];
            }
            else
            {
                $content = $this->getMessage();
            }
            return response()->json([
                'error' => $content,
                'title' => isset($title) ? $title : "Error"
            ]);
        }
        else
        {
            return redirect()->back()->with('error', $this->getMessage());
        }

    }
}
