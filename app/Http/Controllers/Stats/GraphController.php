<?php

namespace App\Http\Controllers\Stats;

use App\Action;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    /**
     * Display a listing of all counts for a specific target
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $target = $request->get("target");
        $occurrence = $request->get("occurrence");

        $class = 'App\\' . $target;

        $content = $class::select(DB::raw("DATE_FORMAT(created_at, '%{$occurrence}') as $occurrence, count(*) as total"));

        if ($occurrence == "M") {
            $year = $request->get("year") ?? Carbon::now()->year;

            $content = $content->whereRaw("DATE_FORMAT(created_at, '%Y')=$year");
        }

        $content = $content->groupByRaw("DATE_FORMAT(created_at, '%{$occurrence}')")->get();

        $rs = [];
        foreach ($content as $value) {
            $rs[$value->$occurrence] = $value->total;
        }

        uksort($rs, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        return response()->json($rs, 200);
    }

    /**
     * Display a listing of all counts for location and action
     *
     * @return \Illuminate\Http\Response
     */
    public function actions(Request $request, $location, $action)
    {
        $occurrence = $request->get("occurrence");

        $content = Action::select(DB::raw("DATE_FORMAT(created_at, '%{$occurrence}') as $occurrence,count(*) as total"))->whereLocation($location)->whereAction($action);
        
        if ($occurrence == "M") {
            $year = $request->get("year") ?? Carbon::now()->year;

            $content = $content->whereRaw("DATE_FORMAT(created_at, '%Y')=$year");
        }

        $content = $content->groupByRaw("DATE_FORMAT(created_at, '%{$occurrence}')")->get();

        $rs = [];
        foreach ($content as $value) {
            $rs[$value->$occurrence] = $value->total;
        }

        uksort($rs, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });
        
        return response()->json($rs, 200);
    }
}
