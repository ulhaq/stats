<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActionResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\VariableResource;
use App\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SessionResource::collection(Session::paginate(10))->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $session = Session::create($request->all());

        return response(new SessionResource($session), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function show(Session $session)
    {
        return response(new SessionResource($session), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function destroy(Session $session)
    {
        $session->delete();

        return response(new SessionResource($session), 200);
    }

    /**
     * Display a listing of the resource's relation.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function actions(Session $session)
    {
        $actions = $session->actions()->paginate(10);

        return ActionResource::collection($actions)->response()->setStatusCode(200);
    }

    /**
     * Display a listing of the resource's relation's relation.
     *
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function variables(Session $session)
    {
        $variables = $session->variables()->paginate(10);

        return VariableResource::collection($variables)->response()->setStatusCode(200);
    }
}