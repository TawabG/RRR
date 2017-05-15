<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Carbon\Carbon;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Target::class, function (Faker\Generator $faker) {
    // base64 image of a QR code.
    $base64encoded_jpeg_str = 'iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6AQMAAACyIsh+AAAABlBMVEUAAAD///+l2Z/dAAABOUlEQVRoQ+3YwY3EIAwFUEspgJLSOiWlACQv/rbZZKNNIOfvAxOYd/oDJhqRqE21Fu1Df5L9kFsRvIFdrY6+LKXZtAmmKIIVcGCwqPuD0x1eNoJPAFH3nRtRE3wGuXP139+C4BmM4w9VZfhbfyB4BFEBcpBbEbwAvVZcSaMqwSzwimw3P/TeTX0gmAX95IvEu2bJvHE5qRKsgIw6Ao4bHouYEkwDnPeaOpNvEqETzIPjeuh9LQeCBYBMtSUteS+lJ5gG+NyikSJqKS0HggVwLs87os7+QDAH/PjLWImp+DVFMAvOKdtohx5P4s2VYBZkoYdmNx3xn4rgDSBgtQ1q91L82YGoo68SzAJ/JYqooa5FsAx+VR1fEHwCqtk5S25fggVgyz7B5e7dFEoJVkBUAgvdb/g/RfAMfgBDgcfgOjY8PQAAAABJRU5ErkJggg==';

    return [
        'id' => $faker->randomNumber(),
        'game_id' => $faker->randomNumber(),
        'image' => substr($base64encoded_jpeg_str, 0, 244),
        'qrcode' => str_random(10),
        'name' => str_random(7)
    ];
});

$factory->define(App\Player::class, function(Faker\Generator $faker){
  return [
    'id' => $faker->randomNumber(),
    'name'=> "tester"
  ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Session::class, function (Faker\Generator $faker) {

    $id = $faker->randomNumber();
    $gameId = $faker->randomNumber();
    $duration_seconds = $faker->numberBetween(1, 59);
    $duration_minutes = $faker->numberBetween(1, 59);
    $duration_hours   = $faker->numberBetween(0, 2);
    $duration = Carbon::create(0,0,0, $duration_hours, $duration_minutes, $duration_seconds);
    $status = 'ongoing';

    echo 'session: ' . "\n";
    echo 'id:' . $id;

    return [
        'id' => $id,
        'game_id' => $gameId,
        'duration' => $duration,
        'status' => $status
    ];

});


$factory->define(App\Game::class, function(Faker\Generator $faker){
  return [
      'id' => $faker->randomNumber(),
      'active' => 1,
      'name' => 'testgame',
      'lat' => '0.0',
      'lon' => '0.0'
  ];
});
