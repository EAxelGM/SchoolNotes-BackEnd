<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Etiqueta;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Etiqueta::class, function (Faker $faker) {
    $etiqueta = $faker->name;
    return [
        'nombre' => $etiqueta,
        'slug' => Str::slug($etiqueta),
        'activo' => $faker->randomElement([1, 0]),
    ];
});
