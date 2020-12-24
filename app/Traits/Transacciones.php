<?php
namespace App\Traits;

use App\HistorialDiamondsClips as DiamondClip;
use App\HistorialClips as Clip;
use App\User;
use App\Traits\Funciones;
use App\CodigoCreador as Codigo;

trait Transacciones{
    use Funciones;

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
                $this->clipsMultiplo($user_recibe, 100);
                Clip::create([
                    'user_paga' => $user_paga->_id,
                    'cantidad_paga' => $costo_apunte,
                    'user_recibe' => $user_recibe->_id,
                    'cantidad_recibe' => $user_recibe_clips,
                    'clips_empresa' => $costo_apunte - $user_recibe_clips,
                    'descripcion' => 'Compra de un apunte',
                    'pregunta_id' => null,
                    'apunte_id' => $apunte->_id,
                    'borrado' => 0,
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
                    'borrado' => 0,
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

    public function desbloquearPortafolio($user_paga, $portafolio, $costo_apunte, $user_recibe_clips, $apuntes_ids_faltantes){
        if($user_paga->clips >= $costo_apunte){
            $portafolios_comprados = isset($user_paga->portafolios_comprados) ? $user_paga->portafolios_comprados : [];
            if(!in_array($portafolio->_id, $portafolios_comprados)){
                array_push($portafolios_comprados, $portafolio->_id);
            }
            $user_paga->portafolios_comprados = $portafolios_comprados;
            $user_paga->clips = $user_paga->clips - $costo_apunte;

            $array_apuntes_user = $user_paga->apuntes_comprados;
            foreach($apuntes_ids_faltantes as $id_apuntes){
                array_push($array_apuntes_user, $id_apuntes);
            }
            $user_paga->apuntes_comprados = $array_apuntes_user;
            $user_paga->save();

            $user_recibe = User::find($portafolio->user_id);
            if($user_recibe){
                $user_recibe->clips = $user_recibe->clips + $user_recibe_clips;
                $user_recibe->save();
                $this->clipsMultiplo($user_recibe, 100);
                Clip::create([
                    'user_paga' => $user_paga->_id,
                    'cantidad_paga' => $costo_apunte,
                    'user_recibe' => $user_recibe->_id,
                    'cantidad_recibe' => $user_recibe_clips,
                    'clips_empresa' => $costo_apunte - $user_recibe_clips,
                    'descripcion' => 'Compra de un portafolio',
                    'pregunta_id' => null,
                    'apunte_id' => $portafolio->_id,
                    'borrado' => 0,
                ]);
            }else{
                Clip::create([
                    'user_paga' => $user_paga->_id,
                    'cantidad_paga' => $costo_apunte,
                    'user_recibe' => null,
                    'cantidad_recibe' => 0,
                    'clips_empresa' => $costo_apunte,
                    'descripcion' => 'Compra de un portafolio',
                    'pregunta_id' => null,
                    'apunte_id' => $portafolio->_id,
                    'borrado' => 0,
                ]);
            }

            $data = [
                'mensaje' => 'Compra realizada con exito!',
                'code' => 200,
            ];
        }else{
            $data = [
                'mensaje' => 'No cuentas con los suficientes clips para comprar este portafolio',
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
            'borrado' => 0,
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
                'borrado' => 0,
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

            $clip = Clip::where([['pregunta_id', $pregunta->_id],['user_paga', $pregunta->user_id]])->first();
            if($clip){
                $clip->user_recibe = $user_recibe->_id;
                $clip->cantidad_recibe = $cantidad;
                $clip->save();
            }
        }
    }

    public function borrarObjeto($relacion, $idObjeto){
        $historial = Clip::where($relacion, $idObjeto)->first();
        if($historial){
            $historial->borrado = 1;
            $historial->save();
        }
    }

    public function registroCodigoCreador($user, $codigo){
        $codigo = Codigo::where([['codigo', $codigo],['activo', 1]])->first();
        if($codigo){
            $user->clips = $user->clips + $codigo->clips_registro;
            $user->save();

            $user_beneficiado = User::find($codigo->user_id);
            if($user_beneficiado){
                $user_beneficiado->clips = $user_beneficiado->clips + 5;
                $user_beneficiado->save();
            }
            $data = [
                'message' => 'Success',
                'code' => 200,
            ];
        }else{
            $data = [
                'message' => 'codigo no encontrado',
                'code' => 404,
            ];
        }

        return $data;
    }
}
