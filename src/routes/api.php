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


Route::get('teams', 'TeamController@index');

Route::post('score/edit', 'WeekController@edit');

Route::post('fixtures', 'WeekController@create');

Route::get('fixtures', 'WeekController@get');

Route::get('simulation/{week}', 'SimulationController@index');

Route::get('simulation/play/{week}', 'SimulationController@play');

Route::get('simulation/play/all/{all}/{week}', 'SimulationController@playAll');

Route::get('simulation/reset/tournament', 'SimulationController@reset');

Route::get('simulation/reset/all', 'SimulationController@resetAll');
