<?php

namespace App\Http\Controllers;

use App\Game;
use App\Target;
use DB;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * @author Tawab Ghorbandi
     *
     * Start creating a game
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        if(isset($request->name) && isset($request->lat) && isset($request->lon)) {
            $game = new Game();
            $game->active = FALSE;
            $game->name = $request->name;
            $game->lat = $request->lat;
            $game->lon = $request->lon;
            $game->save();
            return response()->json(['gameId' => $game->id], 200);
        }
        return response()->json(["error" => "not all field are filled"], 400);
    }

    /**
     * @author Tawab Ghorbandi
     *
     * Finish creating a game
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFinish(Request $request){
        $game = Game::find($request->gameId);
        if($game != null){
            $game->active = TRUE;
            $game->save();
            return response()->json(['gameId' => $game->id], 200);
        }
        return response()->json(['error' => 'Game not Found'], 404);

    }

    /**
     * @author Tawab Ghorbandi
     *
     * Get all games in radius van <5km.
     *
     * @param $lat, $lon
     * @return \Illuminate\Http\JsonResponse
     */

    public function getGames($lat, $lon){

        // query uitvoeren, als de lon en lat die je invuld in 5km radius zit van de lat en lon van database laat games zien
        $distance = 5;


        $query = DB::table('games')
        ->select(DB::raw('id, name, lat, lon, ( 6371 * acos( cos( radians(lat) ) * cos( radians( '.$lat.' ) ) * cos( radians( '.$lon.' ) - radians(lon) ) + sin( radians(lat) ) * sin( radians( '.$lat.' ) ) ) ) AS distance'))
        ->having('distance', '<', $distance)
        ->get();

        $count = 0;

        $success = true;
        while (empty($query) || empty($query[0])){

            $distance = $distance * 2;
            if($distance < 500){
                $count++;
                $query = DB::table('games')
                ->select(DB::raw('id, name, lat, lon, ( 6371 * acos( cos( radians(lat) ) * cos( radians( '.$lat.' ) ) * cos( radians( '.$lon.' ) - radians(lon) ) + sin( radians(lat) ) * sin( radians( '.$lat.' ) ) ) ) AS distance'))
                ->having('distance', '<', $distance)
                ->get();
            }else{
                $query = ['0' => ['Error' => 'There are no games available in your radius'] ];
                $success = false;
            }
        }

        if($success){
            $json = array();

            foreach ($query as $game) {
                $gameForTarget = Game::find($game->id);
                $target = $gameForTarget->targets()->first();
                if(isset($target)){
                    $image = $target->image;
                    if (isset($image)){
                        $game->image = $image;
                    }else{
                        $game->image = null;
                    }
                }else{
                    $game->image = null;
                }
                array_push($json, $game);
            }

            return response()->json($json);
        }else{
            return response()->json($query);
        }
    }

}
