<?php

namespace App\Http\Controllers\Stats;

use App\Action;
use App\Http\Controllers\Controller;
use App\Variable;

class CountController extends Controller
{
    /**
     * Display view counts of $location.
     *
     * @param  string  $location
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function index($location, $id, $action)
    {
        $variables = Variable::whereVariable("{$location}_id")->whereValue($id)->whereHas("action", function ($q) use ($location, $action) {
            $q->whereLocation($location)->whereAction($action);
        })->count();

        return response()->json(["counts" => $variables], 200);
    }
}
