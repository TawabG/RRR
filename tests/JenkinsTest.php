<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JenkinsTest extends TestCase
{
    /**
     * @Author Geert Berkers
     *
     * Create valid a session
     */
    public function testCreatePlayer()
    {
        parent::setUp();
        $player = factory(App\Player::class)->make();
        $player->name = "Geert";
        dump($player);

        self::assertEquals("Geert", $player->name);

    }
}