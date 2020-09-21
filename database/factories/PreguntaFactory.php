<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Pregunta;
use App\Etiqueta;
use App\User;
use Faker\Generator as Faker;

$factory->define(Pregunta::class, function (Faker $faker) {
    $user_count = User::all()->count();
    $users_ids = User::pluck('_id')->all();

    $etiqueta_count = Etiqueta::all()->count();
    $etiquetas_ids = Etiqueta::pluck('_id')->all();

    return [
        'contenido' => $faker->text(rand(10,200)), 
        'user_id' => User::where('activo',1)->get()->random()->_id,
        'etiquetas_ids' => $faker->randomElements($etiquetas_ids, rand(1, 3)),
        'verificado' => $faker->randomElement([1, 0]), 
        'reacciones' => $faker->randomElements($users_ids, rand(0, $user_count)),
        'activo' => $faker->randomElement([1, 0]),
    ];
});
