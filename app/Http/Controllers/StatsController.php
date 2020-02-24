<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatsResource;
use App\Session;

class StatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Session $session)
    {
        $session = $session->load('actions.variables');

        return response()->json(new StatsResource($session), 200);
    }
}
