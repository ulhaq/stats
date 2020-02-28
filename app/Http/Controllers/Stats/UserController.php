<?php

namespace App\Http\Controllers\Stats;

use App\Http\Controllers\Controller;
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
        $session = DB::table('sessions')->select('user', DB::raw('count(*) as total'))->groupBy('user')->get();

        return response()->json(["counts" => $session], 200);
    }
}
