<?php

namespace App\Traits;

use Carbon\Carbon;

//Uso del Faker para respaldar contraseña
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Apunte;
use App\Publicacion;
use App\Traits\Transacciones;
use App\Etiqueta;
use App\User;

trait Generador{
    use Transacciones;
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

    public function bienvenida($user, $clips_free){
        $users_totales = User::where('activo', 1)->count();
        $apunte = Apunte::first();
        /*publicacion = Publicacion::create([
            'contenido' => 'Bienvenido '.$user->name.', muchas gracias por registrarte en SchoolNotes 🥳🥳',  
            'reacciones' => [],
            'activo' => 1, 
            'user_id' => $user->_id,
        ]); */
            
        $this->desbloquearApunte($user, $apunte, 0, $clips_free);
        
    }
}