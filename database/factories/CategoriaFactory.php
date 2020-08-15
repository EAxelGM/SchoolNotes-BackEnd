<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Categoria;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Categoria::class, function (Faker $faker) {
    $categoria = $faker->name;
    return [
        'nombre' => $categoria,
        'slug' => Str::slug($categoria),
        'activo' => $faker->randomElement([1, 0]),
    ];
});
