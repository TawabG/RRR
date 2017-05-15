<?php

use Illuminate\Database\Seeder;

class TargetTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $game = App\Game::orderBy('id', 'desc')->first();

        DB::table('targets')->insert([[
            'game_id' => $game->id,
            'image' => 'targets/test-target-1.jpg',
            'qrcode' => 'somecode',
            'name' => 'target-1',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'game_id' => $game->id,
                'image' => 'targets/test-target-2.jpg',
                'qrcode' => 'somecode',
                'name' => 'target-2',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'game_id' => $game->id,
                'image' => 'targets/test-target-3.jpg',
                'qrcode' => 'somecode',
                'name' => 'target-3',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'game_id' => $game->id,
                'image' => 'targets/test-target-4.jpg',
                'qrcode' => 'somecode',
                'name' => 'target-4',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'game_id' => $game->id,
                'image' => 'targets/test-target-5.jpg',
                'qrcode' => 'somecode',
                'name' => 'target-5',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]]);
    }
}
