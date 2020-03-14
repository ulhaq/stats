<?php

namespace App\Http\Controllers\Stats;

use App\Http\Controllers\Controller;
use App\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display loggins per user.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $times = $request->get("times") ?? 1;

        $session = DB::table("sessions")->select("user", DB::raw("count(*) as total"))->orderBy("total", "desc")->groupBy("user");

        if ($request->get("from") && $request->get("to")) {
            $start_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("from"));
            $end_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("to"));

            $session->havingRaw("count(*) > {$times}")->whereBetween("created_at", [$start_time, $end_time]);
        }

        $session = $session->paginate(10)->appends($request->except("page"));

        return response()->json($session, 200);
    }

    /**
     * Display percentage of returning users.
     *
     * @return \Illuminate\Http\Response
     */
    public function returning(Request $request)
    {
        $times = $request->get("times") ?? 1;

        $totalUsers = Session::distinct("user")->count() > 0 ? Session::distinct("user")->count() : 1;

        $totalReturningUsers = DB::table("sessions")->select("user")->groupBy("user")->havingRaw("count(*) > {$times}");

        if ($request->get("from") && $request->get("to")) {
            $start_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("from"));
            $end_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("to"));

            $totalReturningUsers->whereBetween("created_at", [$start_time, $end_time]);
        }

        $totalReturningUsers = $totalReturningUsers->get()->count();

        $percentage = $totalReturningUsers / $totalUsers * 100;

        return response()->json(["percentage" => $percentage], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sessions(Request $request, $user)
    {
        $sessions = Session::whereUser($user);

        if ($request->get("from") && $request->get("to")) {
            $start_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("from"));
            $end_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("to"));

            $sessions->whereBetween("created_at", [$start_time, $end_time]);
        }

        $sessions = $sessions->orderBy("created_at", "asc")->get(["id", "client", "platform", "created_at"]);

        return response()->json($sessions, 200);
    }
}
