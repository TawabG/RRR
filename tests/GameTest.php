<?php

class GameTest extends TestCase
{
    /**
     * @author Sjors van Mierlo
     *
     * Tests the start session
     **/
    public function testValidStartSesion(){
      $session = factory(App\Session::class)->make();
      $session->save();

      $response = $this->call('POST', 'api/v1/game/session/start', ['sessionId' => $session->id]);
      $json = json_decode($response->content());

      self::assertEquals(200, $response->status());
      self::assertEquals($json->sessionId, $session->id);
    }

    /**
     * @author Sjors van Mierlo
     *
     * Test no input for start session
     **/
    public function testInvalidBodyStartSesion(){
      $response = $this->call('POST', 'api/v1/game/session/start');
      $json = json_decode($response->content());

      self::assertEquals(400, $response->status());
      self::assertEquals($json->error, "not all field are filled");
    }

    /**
     * @author Sjors van Mierlo
     *
     * Test invalid input for start session
     **/
    public function testInvalidSessionStartSesion(){
      $response = $this->call('POST', 'api/v1/game/session/start', ['sessionId' => rand(9000,9999)]);
      $json = json_decode($response->content());

      self::assertEquals(404, $response->status());
      self::assertEquals($json->error, "Session not found");
    }

    /**
     * @author Sjors van Mierlo
     *
     * Test valid input for create game
     **/
    public function testValidCreateGame(){
      $response = $this->call('POST', 'api/v1/game/create', ['name' => 'Testgame', 'lat' => '0.0', 'lon' => '0.0']);
      $json = json_decode($response->content());

      self::assertEquals(200, $response->status());
      self::assertTrue(($json->gameId > 0));
    }

    /**
     * @author Sjors van Mierlo
     *
     * Test invalid input for create game
     **/
    public function testInvalidCreateGame(){
      $response = $this->call('POST', 'api/v1/game/create');
      $json = json_decode($response->content());

      self::assertEquals(400, $response->status());
      self::assertEquals($json->error, 'not all field are filled');
    }

}
