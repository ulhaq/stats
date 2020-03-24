<?php

namespace App\Http\Controllers\Stats;

use App\Action;
use App\Http\Controllers\Controller;
use App\Session;
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

        if ($request->get("from")) {
            $start_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("from"));
        } else {
            $start_time = Carbon::now()->subYear();
        }

        if ($request->get("to")) {
            $end_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("to"));
        } else {
            $end_time = Carbon::now();
        }

        $class = 'App\\' . ucfirst($target);

        $content = $class::select(DB::raw("DATE_FORMAT(created_at, '{$occurrence}') as occurrence, count(*) as total"))->whereBetween("created_at", [$start_time, $end_time])->groupByRaw("DATE_FORMAT(created_at, '{$occurrence}')")->get();

        $rs = [];
        foreach ($content as $value) {
            $rs[$value->occurrence] = $value->total;
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

        if ($request->get("from")) {
            $start_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("from"));
        } else {
            $start_time = Carbon::now()->subYear();
        }

        if ($request->get("to")) {
            $end_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("to"));
        } else {
            $end_time = Carbon::now();
        }

        $content = Action::select(DB::raw("DATE_FORMAT(created_at, '{$occurrence}') as occurrence,count(*) as total"))->whereLocation($location)->whereAction($action)->whereBetween("created_at", [$start_time, $end_time])->groupByRaw("DATE_FORMAT(created_at, '{$occurrence}')")->get();

        $rs = [];
        foreach ($content as $value) {
            $rs[$value->occurrence] = $value->total;
        }

        uksort($rs, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        return response()->json($rs, 200);
    }

    /**
     * Display a listing of all counts of visits per user.
     *
     * @return \Illuminate\Http\Response
     */
    public function users(Request $request)
    {
        if ($request->get("from")) {
            $start_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("from"));
        } else {
            $start_time = Carbon::now()->subYear();
        }

        if ($request->get("to")) {
            $end_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("to"));
        } else {
            $end_time = Carbon::now();
        }

        $session = Session::select("visitor", DB::raw("count(*) as total"))->orderBy("total", "desc")->groupBy("visitor")->whereBetween("created_at", [$start_time, $end_time])->get();

        $rs = [];
        foreach ($session as $value) {
            $rs[$value->visitor] = $value->total;
        }

        return response()->json($rs, 200);
    }

    /**
     * Display a listing of all counts for users origins.
     *
     * @return \Illuminate\Http\Response
     */
    public function origins(Request $request)
    {
        if ($request->get("from")) {
            $start_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("from"));
        } else {
            $start_time = Carbon::now()->subYear();
        }

        if ($request->get("to")) {
            $end_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("to"));
        } else {
            $end_time = Carbon::now();
        }

        $session = Session::select("origin", DB::raw("count(*) as total"))->orderBy("total", "desc")->groupBy("origin")->whereBetween("created_at", [$start_time, $end_time])->get();

        $rs = [];
        foreach ($session as $value) {
            $rs[$value->origin] = $value->total;
        }

        return response()->json($rs, 200);
    }
}
