<?php

namespace App\Http\Controllers;

use App\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * @author Geert Berkers
     *
     * Get the players
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function players(Request $request){
        $players = Player::all();
        return response()->json($players, 200);
    }

    /**
     * @author Tawab Ghorbandi
     *
     * Create a player
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        $name = $request->name;
        if($name != null){
            $player = new Player();
            $player->name = $name;
            $player->save();
            return response()->json(['id' => $player->id], 200);
        }
        return response()->json(['error' => 'Not all field are set'], 404);

    }

    /**
     * @author Maikel Hoeks
     *
     * Update a player
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request){
        $player = Player::find($request->id);
        $name = $request->name;
        if($name != null && $player != null){
            $player->name = $name;
            $player->save();
            return response()->json(['id' => $player->id], 200);
        }
        return response()->json(['error' => 'Not all field are set'], 404);
    }

    /**
     * @author Tawab Ghorbandi
     *
     * Get player highscrores
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHighscore(Request $request){
        $player = Player::find($request->id);

        return response()->json(['highscore' => $player->scores]);
    }
}
