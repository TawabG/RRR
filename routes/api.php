<?php

use Illuminate\Http\Request;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/info', function (Request $request) {
   return response()->json(["name" => "RunForestRun API", "version" => "0.1"] , 200);
});

Route::group(["prefix" => '/v1'], function(){
  Route::group(["prefix" => "/game"], function (){
      Route::post("/create", "GameController@create");
      Route::post("/create/finish", "GameController@createFinish");
      Route::get("/session/{id}", "SessionController@get");
      Route::post("/session", "SessionController@create");
      Route::post("/session/start", "SessionController@start");
      Route::post("/session/join", "SessionController@join");
      Route::post("/session/firsttarget", "SessionController@getFirstTarget");
      Route::get("/session/highscore/{id}", "SessionController@highscore");
      Route::get("/{lat}/{lon}", "GameController@getGames");
      Route::post("/session/found", "SessionController@foundTarget");
      Route::post("/session/leave", "SessionController@leave");
      Route::get("/session/streak/{id}/{playerId}", "SessionController@getStreak");
  });

  Route::group(["prefix" => "/target"], function (){
      Route::post("/add", "TargetController@add");
      Route::get("/get", "TargetController@get");
  });

  Route::group(["prefix" => "/player"], function (){
      Route::get("", "PlayerController@players");
      Route::post("/create", "PlayerController@create");
      Route::post("/update", "PlayerController@update");
  });

});
