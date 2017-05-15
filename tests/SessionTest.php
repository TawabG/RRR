<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SessionTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @Author Sjors van Mierlo
     * Create valid a session
     */
     public function testCreateValidSession(){
       $player = factory(App\Player::class)->make();
       $player->save();
       //echo "PlayerID: " . $player->id;

       $game = factory(App\Game::class)->make();
       $game->save();
       //echo 'Game: ' . $game->id;

       $response = $this->call('post','api/v1/game/session', ['playerId' => $player->id, 'gameId' => $game->id, 'hours' => 0, 'minutes' => 20, 'seconds' => 0]);
       $json = json_decode($response->content());

       self::assertEquals(200, $response->status());
       self::assertTrue((strlen($json->SessionId)) > 0);
     }

     /*
      * @Author Sjors van Mierlo
      * Create a invald session which returns a 400 status code.
      */
     public function testCreateInvalidSession(){
      $response = $this->call('post','api/v1/game/session');
      self::assertEquals(400, $response->status());
     }

     /**
      * @Author Etienne Cooijmans
      * Tests if the correct session is returned by the API for a given session id.
      */
     public function testGetSingleEmptySession()
     {
         $sesh = factory(App\Session::class)->make();
         //$sesh->game_id = 42;

         echo 'Inserting session into database: ' . $sesh . "\n";

         $sesh->save();

         $response = $this->call("GET", "api/v1/game/session/" . $sesh->id);

         echo 'Response: ' . $response->content() . "\n";

         $answer = json_decode($response->content());

         $expected_players = [];
         $expected_duration = $sesh->duration->minute;
         self::assertEquals($sesh->id,           $answer->id);
         self::assertEquals($sesh->game_id,      $answer->game_id);
         self::assertEquals($expected_duration,  \Carbon\Carbon::parse($answer->duration)->minute);
         self::assertEquals($sesh->status,       $answer->status);
         self::assertEquals($sesh->created_at,   $answer->created_at);
         self::assertEquals($sesh->updated_at,   $answer->updated_at);
         self::assertEquals($expected_players,   $answer->players);

         // Unusable because players are added in the response,
         // unless you pre-edit the $sesh json.
         // self::assertEquals($sesh, $response->content());
     }

     public function testValidJoinSession(){
       $player1 = factory(App\Player::class)->make();
       $player1->save();

       $player2 = factory(App\Player::class)->make();
       $player2->save();

       //echo "PlayerID: " . $player->id;

       $game = factory(App\Game::class)->make();
       $game->save();
       //echo 'Game: ' . $game->id;

       $response = $this->call('post','api/v1/game/session', ['playerId' => $player1->id, 'gameId' => $game->id, 'hours' => 0, 'minutes' => 20, 'seconds' => 0]);
       $json = json_decode($response->content());

       self::assertEquals(200, $response->status());
       self::assertTrue((strlen($json->SessionId)) > 0);

       $response2 = $this->call('post', 'api/v1/game/session/join', ['sessionId' => $json->SessionId, 'name' => $player2->name]);
       $json2 = json_decode($response2->content());
       self::assertEquals(200, $response2->status());
       self::assertEquals($json2->sessionId, $json->SessionId);
     }

     public function testInvalidJoinSession(){
       $response = $this->call('post', 'api/v1/game/session/join');
       self::assertEquals(400, $response->status());
     }
}
