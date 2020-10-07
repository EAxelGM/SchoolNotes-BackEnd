<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Apunte;
use Faker\Generator as Faker;
use App\Etiqueta;
use App\User;

$factory->define(Apunte::class, function (Faker $faker) {
    $user_count = User::all()->count();
    $users_ids = User::pluck('_id')->all();

    $etiqueta_count = Etiqueta::all()->count();
    $etiquetas_ids = Etiqueta::pluck('_id')->all();

    $titulo = $faker->text(rand(10,15)).' '.rand(123456,654321);
    return [
        'titulo' => $titulo,
        'slug' => Str::slug($titulo),
        'archivo' => 'https://www.redalyc.org/pdf/325/32510017.pdf',
        'descripcion' => '',
        'user_id' => User::where('activo',1)->get()->random()->_id,
        'etiquetas_ids' => $faker->randomElements($etiquetas_ids, rand(1, 3)),
        'reacciones' => $faker->randomElements($users_ids, rand(0, $user_count)),
        'activo' => $faker->randomElement([1, 0]),
    ];
});
