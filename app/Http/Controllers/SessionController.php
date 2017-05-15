<?php
namespace App\Http\Controllers;

use App\Events\SessionPlayersUpdated;
use App\Events\SessionStatusUpdated;
use App\Jobs\SendGameEnded;
use App\Session as Session;
use App\Player as Player;
use App\Score as Score;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Collection;

class SessionController extends Controller
{
     /**
      * @author Sjors van Mierlo
      *
      * Gets an session by ID.
      *
      * @param $id sessionId.
      * @return \Illuminate\Http\JsonResponse
      */
     public function get($id){

      //Look for Session with primary key $id.
      $session = Session::findOrFail($id);

      $session->players = $session->players()->get();

      //Checks if session is found
      if($session == null){
        //No session found. Return status 404
        return response()->json(['error' => 'Session not Found'],404);
      }

      //Returns found session with primary key $id.
      return response()->json($session,200);
    }


    /**
     * @author Sjors van Mierlo
     *
     * Creates a new session.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){

      if(isset($request->playerId) && isset($request->gameId) && isset($request->hours) && isset($request->minutes) && isset($request->seconds)){

        $duration_seconds = $request->seconds;
        $duration_minutes = $request->minutes;
        $duration_hours   = $request->hours;
        $duration = Carbon::create(0,0,0, $duration_hours, $duration_minutes, $duration_seconds);

        $session = new Session();
        $session->game_id = $request->gameId;
        $session->duration = $duration;
        $session->status = "WAITING";
        $session->save();
        $session->players()->attach($request->playerId);
        $player = Player::findOrFail($request->playerId);
        $score = new Score();
        $score->points = 0;
        $score->player()->associate($player);
        $score->session()->associate($session);
        $score->save();
        event(new SessionPlayersUpdated($session));
          //fix me sessionId
        return response()->json(["SessionId" => $session->id], 200);
      }

      return response()->json(['error' => 'Not all fields are filled'], 400);
    }

    /**
     * @author Sjors van Mierlo
     *
     * Joins a existing session.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function join(Request $request){

      if(isset($request->name) && isset($request->sessionId)){
        $session = Session::find($request->sessionId);

        if($session->status != "WAITING"){
          return response()->json(['error' => 'Session is not waiting for new players.'], 400);
        }else{
          $player = new Player();
          $player->name = $request->name;
          $player->save();

          $session->players()->attach($player->id);
          $score = new Score();
          $score->points = 0;
          $score->player()->associate($player);
          $score->session()->associate($session);
          $score->save();
          event(new SessionPlayersUpdated($session));

          return response()->json(['sessionId' => $session->id, 'playerId' => $player->id, 'duration' => $session->duration],200);
        }
      } else if (isset($request->playerId) && isset($request->sessionId)){
          $player = Player::findOrFail($request->playerId);
          $session = Session::findOrFail($request->sessionId);

          $session->players()->attach($player->id);
          $score = new Score();
          $score->points = 0;
          $score->player()->associate($player);
          $score->session()->associate($session);
          $score->save();
          event(new SessionPlayersUpdated($session));

          return response()->json(['sessionId' => $session->id, 'playerId' => $player->id, 'duration' => $session->duration],200);
      }

      return response()->json(['error' => 'Not all fields are filled'], 400);
    }


    /**
     * @author Tawab Ghorbandi, Geert Berkers
     *
     * As a user, I want to be able to leave a session so I won't be included in the game
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function leave(Request $request){
        if (isset($request->playerId) && isset($request->sessionId)){
            $player = Player::findOrFail($request->playerId);
            $session = Session::findOrFail($request->sessionId);

            if($session->players()->where('player_id', $player->id)->first() !== null){

              $session->players()->detach($player->id);
                event(new SessionPlayersUpdated($session));

              $score = $session->scores()->where('player_id', $player->id)->firstOrFail();

              if(isset($score)) {
                $score->player()->dissociate();
                $score->session()->dissociate();
                $score->save();
                $score->delete();
                return response()->json(['success' => 'Left session'], 200);

              } else {
                  return response()->json(['error' => 'Score not found!'], 400);
              }

            } else {
                return response()->json(['error' => 'No player found'], 400);
            }

        } else {
            return response()->json(['error' => 'Not all fields are filled'], 400);
        }
    }

    /**
     * @author Casper Schobers, Geert Berkers, Maikel Hoeks
     *
     * changes the session status to started and throws an event for sending message with pusher
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function start(Request $request){
      if(isset($request->sessionId)){
        $session = Session::find($request->sessionId);
        if($session != null){
          if(isset($request->hours) && isset($request->minutes) && isset($request->seconds)){
            $duration_seconds = $request->seconds;
            $duration_minutes = $request->minutes;
            $duration_hours   = $request->hours;
            $duration = Carbon::create(0,0,0, $duration_hours, $duration_minutes, $duration_seconds);
            $session->duration = $duration;
          }
          $session->status = "STARTED";
          $session->save();
          event(new SessionStatusUpdated($session));

          $this->ended($session);
          return response()->json(["sessionId" => $session->id], 200);
        }
        return response()->json(["error" => "Session not found"], 404);
      }
      return response()->json(["error" => "not all field are filled"], 400);
    }

    /**
     * @author Geert Berkers, Maikel Hoeks
     *
     * changes the session status to ended and throws an event for sending message with pusher
     *
     * @param Session $session
     */
    public function ended(Session $session){
      $dt = Carbon::parse($session->duration);
      $job = (new SendGameEnded($session))
      ->delay(Carbon::now()
        ->addSeconds($dt->second)
        ->addMinutes($dt->minute)
        ->addHours($dt->hour));

      dispatch($job);
    }

    /**
     * @author Geert Berkers, Tawab Ghorbandi
     *
     * get the first target of the session
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFirstTarget(Request $request){
      if(isset($request->sessionId)){
        $session = Session::findOrFail($request->sessionId);

        if(isset($request->playerId)){
          $player = Player::findOrFail($request->playerId);

            $target = $this->getNextTarget($session, $player);
            //dd($target);

          return response()->json(["imageUrl" => $target->image], 200);
        }
        return response()->json(["error" => "Player not found"], 404);
      }
      return response()->json(["error" => "Session not found"], 404);
    }

    public function getNextTarget(Session $session, Player $player){

      $targets = $session->game->targets;
      do {
        $randomTarget = $targets->random(1);
      } while ($randomTarget->id == $player->target_id);

      $player->target_id = $randomTarget->id;
      $player->save();
      return $randomTarget;
    }

    /**
     * @author Geert Berkers, Tawab Ghorbandi
     *
     * Get the highscores of a session
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function highscore(Request $request){
      if(isset($request->id)){
        $session = Session::find($request->id);
        $player = Player::find($request->playerId);

        if($session != null){
          $scores = $session->scores()->orderBy('points', 'desc')->get();
          if($scores != null) {

            $highscore = array();

            // get highest scoring player.
            $highest_player = $session->scores()->orderBy("highest_streak", "desc")->get()->first()->player_id;

            foreach ($scores as $score) {
              $personalScore = new \stdClass;
              $name = $score->player->name;
              $points = $score->points;

              $playerid = $score->player->id;

              $personalScore->name =$name;
              $personalScore->score =$points;
              $personalScore->playerid=$playerid;

              $personalScore->streak = $playerid == $highest_player;

              array_push($highscore, $personalScore);
            }

            return response()->json(["scores" => $highscore], 200);
          }
          return response()->json(["error" => "Score not found"], 404);
        }
        return response()->json(["error" => "Session not found"], 404);
      }
      return response()->json(["error" => "not all field are filled"], 400);
    }

    /**
     * @author Tawab Ghorbandi, Geert Berkers
     *
     * Get the streak of a session / playerId
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStreak(Request $request){

      $session = Session::find($request->id);
      $player = Player::find($request->playerId);

      if($session != null && $player != null){

        $score = $session->scores()->where('player_id', $player->id)->first();
        if ($score != null){
          return response()->json(["name" => $player->name, "streak" => $score->streak], 200);
        }

      }
      return response()->json(["error" => "not all fields are filled"], 400);
    }

    /**
     * @author Maikel Hoeks
     *
     * Check if a target is the correct found target
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function foundTarget(Request $request){
     $session = Session::find($request->sessionId);
     $player = Player::find($request->playerId);
     $targetId = $request->targetId;

     if($player != null && $targetId != null && $session != null){
      if($player->target->id == $targetId) {
          $nextTarget = $this->getNextTarget($session, $player);
          $score = $session->scores()->where('player_id', $player->id)->first();
          if ($score != null) {
              $score->points = $score->points + 1;
              $score->streak = $score->streak + 1;
              if ($score->streak > $score->highest_streak) {
                  $score->highest_streak = $score->streak;
              }
              $score->save();
              return response()->json(["imageUrl" => $nextTarget->image, "streak" => $score->streak], 200);
          }
          return response()->json(["error" => "Score not set"], 404);
      }
      $score = $session->scores()->where('player_id', $player->id)->first();
         if ($score == null)
             return response()->json(["error" => "Score not set"], 404);
      $score->streak = 0;
      $score->save();
      return response()->json(["error" => "Wrong target"], 404);
    }
    return response()->json(["error" => "not all fields are filled"], 404);
  }
}
