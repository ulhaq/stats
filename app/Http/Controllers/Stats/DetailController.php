<?php

namespace App\Http\Controllers\Stats;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailResource;
use App\Session;

class DetailController extends Controller
{
    /**
     * Display a listing of the resource with its relations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Session $session)
    {
        $session = $session->load('actions.variables');

        return response()->json(new DetailResource($session), 200);
    }
}
