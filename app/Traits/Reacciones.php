<?php

namespace App\Traits;

use App\Traits\EnviarCorreos;
use app\Publicacion;
use app\User;
use app\Comentario;

trait Reacciones {
    use EnviarCorreos;
    
    public function darReaccion($tipo, $user){
        $reacciones = $tipo->reacciones;
        array_push($reacciones, $user->_id);
        $tipo->reacciones = $reacciones;
        $tipo->save();

        $data['mensaje'] = 'Le diste me gusta';
        $data['code'] = 200;
        return $data;
    }

    public function quitarReaccion($tipo, $user){
        $reacciones = $tipo->reacciones;
        $clave = array_search($user->_id, $reacciones);
        unset($reacciones[$clave]);
        $reacciones = array_values($reacciones);
        $tipo->reacciones = $reacciones;
        $tipo->save();

        $data['mensaje'] = 'Has quitado tu me gusta';
        $data['code'] = 200;
        return $data;
    }

}