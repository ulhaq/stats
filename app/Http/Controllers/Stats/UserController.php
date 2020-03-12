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
    public function login()
    {
        $session = DB::table("sessions")->select("user", DB::raw("count(*) as total"))->orderBy("total", "desc")->groupBy("user")->get();

        return response()->json(["counts" => $session], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sessions(Request $request, $user)
    {
        $sessions = Session::whereUser($user);
        
        if($request->get("from") && $request->get("to")){
            $start_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("from"));
            $end_time = Carbon::createFromFormat('Y-m-d\TH:i', $request->get("to"));

            $sessions->whereBetween("created_at", [$start_time, $end_time]);
        }

        $sessions = $sessions->orderBy("created_at", "asc")->get(["id", "client", "platform", "created_at"]);

        return response()->json($sessions, 200);
    }
}
