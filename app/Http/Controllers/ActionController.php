<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActionResource;
use App\Http\Resources\VariableResource;
use App\Action;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ActionResource::collection(filter(Action::paginate(10)))->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $action = Action::create($request->except("variables"));
        $action->variables()->createMany($request->get("variables"));

        return response(new ActionResource($action->load("variables")), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function show(Action $action)
    {
        return response(new ActionResource(filter($action)), 200);
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
     * @param  \App\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function destroy(Action $action)
    {
        $action->delete();

        return response(new ActionResource($action), 200);
    }

    /**
     * Display a listing of the resource's relation.
     *
     * @param  \App\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function variables(Action $action)
    {
        $variables = $action->variables()->paginate(10);

        return VariableResource::collection(filter($variables))->response()->setStatusCode(200);
    }
}
