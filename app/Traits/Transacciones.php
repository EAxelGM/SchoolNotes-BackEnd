<?php
namespace App\Traits;

use App\HistorialDiamondsClips as DiamondClip;
use App\HistorialClips as Clip;
use App\User;

trait Transacciones{

    public function desbloquearApunte($user_paga, $apunte, $costo_apunte, $user_recibe_clips){
        if($user_paga->clips >= $costo_apunte){
            $apuntes_comprados = $user_paga->apuntes_comprados;
            if(in_array($apunte->_id, $apuntes_comprados)){
                return $data = [
                    'mensaje' => 'Ya has comprado -'.$apunte->titulo.'- anteriormente',
                    'code' => 403,
                ];
            }                                                                                                                                                                                                                  
            array_push($apuntes_comprados, $apunte->_id);

            $user_paga->apuntes_comprados = $apuntes_comprados;
            $user_paga->clips = $user_paga->clips - $costo_apunte;
            $user_paga->save(); 

            $user_recibe = User::find($apunte->user_id);
            if($user_recibe){
                $user_recibe->clips = $user_recibe->clips + $user_recibe_clips;
                $user_recibe->save();
                Clip::create([
                    'user_paga' => $user_paga->_id,
                    'cantidad_paga' => $costo_apunte,
                    'user_recibe' => $user_recibe->_id,
                    'cantidad_recibe' => $user_recibe_clips,
                    'clips_empresa' => $costo_apunte - $user_recibe_clips,
                    'descripcion' => 'Compra de un apunte',
                    'pregunta_id' => null,
                    'apunte_id' => $apunte->_id,
                ]);
            }else{
                Clip::create([
                    'user_paga' => $user_paga->_id,
                    'cantidad_paga' => $costo_apunte,
                    'user_recibe' => null,
                    'cantidad_recibe' => 0,
                    'clips_empresa' => $costo_apunte,
                    'descripcion' => 'Compra de un apunte',
                    'pregunta_id' => null,
                    'apunte_id' => $apunte->_id,
                ]);
            }
            
            $data = [
                'mensaje' => 'Compra realizada con exito!',
                'code' => 200,
            ];
        }else{
            $data = [
                'mensaje' => 'No cuentas con los suficientes clips para comprar este apunte',
                'code' => 421,
            ];
        }
        return $data;


    }

    public function pagoApunte($user,$apunte,$cantidad){
        $user->clips = $user->clips+$cantidad;
        $user->save();
        
        Clip::create([
            'user_paga' => null,
            'cantidad_paga' => 0,
            'user_recibe' => $user->_id,
            'cantidad_recibe' => $cantidad,
            'clips_empresa' => -$cantidad,
            'descripcion' => 'Subir un apunte',
            'pregunta_id' => null,
            'apunte_id' => $apunte->_id,
        ]);
        
    }

    public function creacionPregunta($user, $pregunta, $costo_pregunta){
        if($user->clips >= $costo_pregunta){
            $user->clips = $user->clips - $costo_pregunta;
            $user->save();
            
            Clip::create([
                'user_paga' => $user->_id,
                'cantidad_paga' => $costo_pregunta,
                'user_recibe' => null,
                'cantidad_recibe' => 0,
                'clips_empresa' => $costo_pregunta,
                'descripcion' => 'Creo una Pregunta',
                'pregunta_id' => $pregunta->_id,
                'apunte_id' => null,
            ]);

            $data = [
                'mensaje' => 'Pregunta creada.!',
                'code' => 200,
            ];
        }else{
            $pregunta->delete();
            $data = [
                'mensaje' => 'No cuentas con los suficientes clips hacer una pregunta',
                'code' => 421,
            ];
        }

        return $data;
    }

    public function verificacionRespuesta($respuesta, $pregunta, $cantidad){
        $user_recibe = User::find($respuesta->user_id);
        if($user_recibe){
            $user_recibe->clips = $user_recibe->clips + $cantidad;
            $user_recibe->save();

            $clip = Clip::where([['publicacion_id', $publicacion->_id],['user_paga', $publicacion->user_id]])->first();
            $clip->user_recibe = $$user_recibe->_id;
            $clip->cantidad_recibe = $cantidad;
            $clip->save();
        }
    }
}