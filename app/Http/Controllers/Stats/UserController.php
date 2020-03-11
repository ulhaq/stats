<?php

namespace App\Http\Controllers\Stats;

use App\Http\Controllers\Controller;
use App\Session;
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
        $session = DB::table('sessions')->select('user', DB::raw('count(*) as total'))->orderBy('total', 'desc')->groupBy('user')->get();

        return response()->json(["counts" => $session], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sessions($user)
    {
        $sessions = Session::whereUser($user)->get(["id", "client", "platform", "created_at"]);

        return response()->json($sessions, 200);
    }
}
