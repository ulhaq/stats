<?php

namespace App\Http\Controllers\Stats;

use App\Action;
use App\Http\Controllers\Controller;
use App\Variable;
use Illuminate\Http\Request;

class CountController extends Controller
{
    /**
     * Display a listing of all locations and actions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $content = Action::select("location")->distinct()->get()->pluck("location");

        if ($request->get("location") && !$request->get("action")) {
            $content = Action::select("action")->distinct()->whereLocation($request->get("location"))->get()->pluck("action");
        }

        if ($request->get("location") && $request->get("action")) {
            $content = Variable::select("variable")->distinct()->whereHas("action", function ($q) use ($request) {
                $q->whereLocation($request->get("location"))->whereAction($request->get("action"));
            })->get()->pluck("variable");
        }

        return response()->json($content, 200);
    }

    /**
     * Display view counts of $location.
     *
     * @return \Illuminate\Http\Response
     */
    public function counts(Request $request)
    {
        if (!$request->get("variables")) {
            $counts = Variable::whereHas("action", function ($q) use ($request) {
                $q->whereLocation($request->get("location"))->whereAction($request->get("action"));
            })->count();
        } else {
            $counts = Variable::whereIn("variable", array_keys($request->get("variables")))->whereIn("value", array_values($request->get("variables")))->whereHas("action", function ($q) use ($request) {
                $q->whereLocation($request->get("location"))->whereAction($request->get("action"));
            })->count();
        }

        return response()->json(["counts" => $counts], 200);
    }
}
