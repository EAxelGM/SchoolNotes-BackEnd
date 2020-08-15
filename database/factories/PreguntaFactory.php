<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Pregunta;
use App\User;
use Faker\Generator as Faker;

$factory->define(Pregunta::class, function (Faker $faker) {
    $user_count = User::all()->count();
    $users_ids = User::pluck('_id')->all();

    return [
        'contenido' => $faker->text(rand(10,200)), 
        'user_id' => User::where('activo',1)->random()->_id,
        'verificado' => $faker->randomElement([1, 0]), 
        'reacciones' => $faker->randomElements($users_ids, rand(0, $user_count)),
    ];
});
