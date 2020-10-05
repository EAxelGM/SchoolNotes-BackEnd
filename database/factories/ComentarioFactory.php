<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comentario;
use App\User;
use Faker\Generator as Faker;

$factory->define(Comentario::class, function (Faker $faker) {

    $bandera = rand(0,1);
    $user_count = User::all()->count();
    $users_ids = User::pluck('_id')->all();

    if($bandera == 1){
        return [
            'apunte_id' => null,
            'publicacion_id' => App\Publicacion::all()->random()->_id,
            'comentario' => $faker->text(rand(20,100)),
            'reacciones' => $faker->randomElements($users_ids, rand(0, $user_count)),
            'user_id' => User::where('activo',1)->get()->random()->_id,
        ];
    }else{
        return [
            'apunte_id' => App\Apunte::all()->random()->_id,
            'publicacion_id' => null,
            'comentario' => $faker->text(rand(20,100)),
            'reacciones' => $faker->randomElements($users_ids, rand(0, $user_count)), 
            'user_id' => User::where('activo',1)->get()->random()->_id,
        ];
    }
});