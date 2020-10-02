<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Validaciones;
use App\Traits\Reacciones;
use App\Publicacion;
use App\Comentario;
use App\Respuesta;
use App\Pregunta;
use App\User;

class ReaccionController extends Controller
{
    use Validaciones,Reacciones;

    public function index(Request $request){
        $tipo = $request->tipo;

        $user = User::find($request->user_id);
        $data = $this->userActivoVerificado($user);
        if($data['code'] != 200){
            return response()->json([
                'message' => $data['mensaje'],
            ],$data['code']); 
        }


        switch($tipo){
            case 'apunte':
                $data = $this->apunte($request->tipo_id,$user);
            break;

            case 'publicacion':
                $data = $this->publicacion($request->tipo_id,$user);
            break;

            case 'comentario':
                $data = $this->comentario($request->tipo_id,$user);
            break;

            case 'pregunta':
                $data = $this->pregunta($request->tipo_id,$user);
            break;

            case 'respuesta':
                $data = $this->respuesta($request->tipo_id,$user);
            break;
            
            default:
                $data['mensaje'] = 'Oops... hubo un error.';
                $data['code'] = 421;
            break;
        }

        return response()->json([
            'message' => $data['mensaje'],
        ],$data['code']);
    }

    public function apunte($apunte_id, $user){
        $data['mensaje'] = 'Los apuntes aun estan en desarrollo, no se puede reaccionar.';
        $data['code'] = 421;
        return $data;
    }

    public function publicacion($publicacion_id, $user){
        $publicacion = Publicacion::find($publicacion_id);
        $data = $this->publicacionActivo($publicacion);
        if($data['code'] != 200){
            return $data;
        }
        if(in_array($user->_id,$publicacion->reacciones)){
            $data = $this->quitarReaccion($publicacion, $user);
        }else{
            $data = $this->darReaccion($publicacion, $user);
        }
        return $data;
    }

    public function comentario($comentario_id, $user){
        $comentario = Comentario::find($comentario_id);
        $data = $this->comentarioActivo($comentario);
        if($data['code'] != 200){
            return $data;
        }
        if(in_array($user->_id,$comentario->reacciones)){
            $data = $this->quitarReaccion($comentario, $user);
        }else{
            $data = $this->darReaccion($comentario, $user);
        }
        return $data;
    }

    public function pregunta($pregunta_id, $user){
        $pregunta = Pregunta::find($pregunta_id);
        $data = $this->preguntaActivo($pregunta);
        if($data['code'] != 200){
            return $data;
        }
        if(in_array($user->_id,$pregunta->reacciones)){
            $data = $this->quitarReaccion($pregunta, $user);
        }else{
            $data = $this->darReaccion($pregunta, $user);
        }
        return $data;
    }

    public function respuesta($respuesta_id, $user){
        $respuesta = Respuesta::find($respuesta_id);
        $data = $this->respuestaActivo($respuesta);
        if($data['code'] != 200){
            return $data;
        }
        if(in_array($user->_id,$respuesta->reacciones)){
            $data = $this->quitarReaccion($respuesta, $user);
        }else{
            $data = $this->darReaccion($respuesta, $user);
        }
        return $data;
    }
}
