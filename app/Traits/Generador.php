<?php

namespace App\Traits;

use Carbon\Carbon;

//Uso del Faker para respaldar contraseña
use Faker\Generator as Faker;
use Illuminate\Support\Str;

trait Generador{
    public function tokenCorreo($user){
        $token = Str::random(100);

        $expira = Carbon::now()->format('d');
        $expira = $expira+1;

        $user->token_verificacion = [
            'token' => $token,
            'expira' => Carbon::now()->format('Y-m-'.$expira.' H:i:s')
        ];
        $user->save();

        return $user;
    }
}