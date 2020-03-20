<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return;
});

Route::post('sessions', 'SessionController@store')->name('sessions.store');
Route::post('actions', 'ActionController@store')->name('actions.store');
Route::post('variables', 'VariableController@store')->name('variables.store');

Route::middleware('auth:airlock')->group(function () {
    Route::resource('users', 'UserController')->except(['update']);
    Route::resource('sessions', 'SessionController')->except(['store', 'update']);
    Route::resource('actions', 'ActionController')->except(['store', 'update']);
    Route::resource('variables', 'VariableController')->except(['store', 'update']);

    Route::get('sessions/{session}/actions', 'SessionController@actions')->name('sessions.actions');
    Route::get('sessions/{session}/variables', 'SessionController@variables')->name('sessions.variables');
    Route::get('actions/{action}/variables', 'ActionController@variables')->name('actions.variables');

    Route::prefix('stats')->namespace('Stats')->group(function () {
        Route::get('counts', 'CountController@index')->name('stats.count');
        Route::post('counts', 'CountController@counts')->name('stats.count.counts');

        Route::get('visitors/visits', 'VisitorController@visits')->name('stats.visitor.visits');
        Route::get('visitors/returning', 'VisitorController@returning')->name('stats.visitor.returning');
        Route::get('visitors/{visitor}', 'VisitorController@sessions')->name('stats.visitor.sessions');
    });
});
