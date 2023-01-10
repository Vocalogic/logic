<?php

namespace App\Http\Controllers;

use App\Models\LOFile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{

    /**
     * Stream the download of a file. Most likely from a download link.
     * @param string  $hash
     * @param Request $request
     * @return StreamedResponse
     * @throws Exception
     */
    public function get(string $hash, Request $request) : string|StreamedResponse
    {
        $x = explode(".", $hash);
        if (isset($x[1]))
        {
            $hash = $x[0];
        }
        $file = LOFile::whereHash($hash)->first();
        if (!$file) abort(404);
     //   ob_end_clean();
        return _file($file->id, true, !$request->embed);
    }
}
