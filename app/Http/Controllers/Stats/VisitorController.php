<?php

namespace App\Http\Controllers\Stats;

use App\Http\Controllers\Controller;
use App\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    /**
     * Display visits per visitor.
     *
     * @return \Illuminate\Http\Response
     */
    public function visits(Request $request)
    {
        $times = $request->get("times") ?? 1;

        $session = DB::table("sessions")->select("visitor", DB::raw("count(*) as total"))->orderBy("total", "desc")->groupBy("visitor");

        if ($request->get("from") && $request->get("to")) {
            $start_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("from"));
            $end_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("to"));

            $session->havingRaw("count(*) > {$times}")->whereBetween("created_at", [$start_time, $end_time]);
        }

        $session = $session->paginate(10)->appends($request->except("page"));

        return response()->json($session, 200);
    }

    /**
     * Display percentage of returning visitors.
     *
     * @return \Illuminate\Http\Response
     */
    public function returning(Request $request)
    {
        $times = $request->get("times") ?? 1;

        $totalVisitors = Session::distinct("visitor")->count() > 0 ? Session::distinct("visitor")->count() : 1;

        $totalReturningVisitors = DB::table("sessions")->select("visitor")->groupBy("visitor")->havingRaw("count(*) > {$times}");

        if ($request->get("from") && $request->get("to")) {
            $start_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("from"));
            $end_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("to"));

            $totalReturningVisitors->whereBetween("created_at", [$start_time, $end_time]);
        }

        $totalReturningVisitors = $totalReturningVisitors->get()->count();

        $percentage = $totalReturningVisitors / $totalVisitors * 100;

        return response()->json(["percentage" => $percentage], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sessions(Request $request, $visitor)
    {
        $sessions = Session::whereVisitor($visitor);

        if ($request->get("from") && $request->get("to")) {
            $start_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("from"));
            $end_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("to"));

            $sessions->whereBetween("created_at", [$start_time, $end_time]);
        }

        $sessions = $sessions->orderBy("created_at", "desc")->get(["id", "client", "platform", "created_at"]);

        return response()->json($sessions, 200);
    }
}
