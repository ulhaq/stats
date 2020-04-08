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

        if ($request->get("location") && $request->get("action") && !$request->get("target")) {
            $content = Action::select("target")->distinct()->whereLocation($request->get("location"))->whereAction($request->get("action"))->get()->pluck("target");
        }

        if ($request->get("location") && $request->get("action") && $request->get("target")) {
            $content = Variable::select("variable")->distinct()->whereHas("action", function ($q) use ($request) {
                $q->whereLocation($request->get("location"))->whereAction($request->get("action"))->whereTarget($request->get("target"));
            })->get()->pluck("variable");
        }

        return response()->json($content, 200);
    }

    /**
     * Display view counts of location.
     *
     * @return \Illuminate\Http\Response
     */
    public function counts(Request $request)
    {
        $counts = new Action;
        
        if ($request->get("location")) {
            $counts = $counts->whereLocation($request->get("location"));
        }

        if ($request->get("action")) {
            $counts = $counts->whereAction($request->get("action"));
        }

        if ($request->get("target")) {
            $counts = $counts->whereTarget($request->get("target"));
        }

        if ($request->get("variables")) {
            $counts = $counts->whereHas("variables", function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    foreach ($request->get("variables") as $key => $value) {
                        $q->orWhere("variable", $key)->whereValue($value);
                    }
                });
            });
        }

        return response()->json(["counts" => $counts->count()], 200);
    }
}
