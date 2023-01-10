<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Operations\Core\GraphSeries;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GraphController extends Controller
{
    /**
     * Get a Graph
     * @param string  $type
     * @param Request $request
     * @return JsonResponse
     */
    public function show(string $type, Request $request):JsonResponse
    {
        $g = new GraphSeries($type, $request);
        return response()->json($g->run());
    }

}
