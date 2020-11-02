<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Etiqueta;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {

    $dia = $faker->numberBetween(1,31);
    if($dia == 31){
        $mes = $faker->randomElement([1,3,5,7,8,10,12]);
    }elseif($dia == 28){
        $mes = $faker->randomElement([1,3,4,5,6,7,8,9,10,11,12]);
    }else{
        $mes = $faker->numberBetween(1,12);
    }
    $anio = $faker->numberBetween(1980, 2015);

    $etiqueta_count = Etiqueta::all()->count();
    $etiquetas_ids = Etiqueta::pluck('_id')->all();

    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => Hash::make('123'),
        'correo_verificado' => $faker->randomElement([true, false]),
        'descripcion_perfil' => $faker->text(rand(20,200)),
        'fecha_nacimiento' => $anio.'-'.$mes.'-'.$dia,
        'img_perfil' => $faker->randomElement([
            'https://schoolnotes.live/020040sn897200/img_perfiles/default.png',
            'https://schoolnotes.live/020040sn897200/img_perfiles/default1.png',
            'https://schoolnotes.live/020040sn897200/img_perfiles/default2.png',
            'https://schoolnotes.live/020040sn897200/img_perfiles/default3.png',
            'https://schoolnotes.live/020040sn897200/img_perfiles/default4.png',
        ]),
        'seguidos' => [],
        'seguidores' => [],
        'etiquetas_ids' => $faker->randomElements($etiquetas_ids, rand(1, 3)),
        'clips' => $faker->numberBetween(0,9999),
        'diamond_clips' => $faker->numberBetween(0,999),
        'apuntes_comprados' => [],
        'tipo' => 'usuario',
        'activo' => $faker->randomElement([1, 0]),
    ];
});
