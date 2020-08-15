<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Respuesta;
use App\User;
use Faker\Generator as Faker;

$factory->define(Respuesta::class, function (Faker $faker) {
    $user_count = User::all()->count();
    $users_ids = User::pluck('_id')->all();
    return [
        'contenido' => $faker->text(rand(10,200)), 
        'user_id' => User::where('activo',1)->random()->_id,
        'pregunta_id' => App\Pregunta::all()->random()->_id, 
        'reacciones' => $faker->randomElements($users_ids, rand(0, $user_count)),
    ];
});
