<?php

namespace App\Traits;

use App\Traits\EnviarCorreos;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

trait Validaciones {
    use EnviarCorreos;

    public function userActivoVerificado($user){
        $code = $user ? true : false;
        if($code){
            if($user->activo != 1){
                $data['mensaje'] = "Lo sentimos $user->name, pero tu cuenta esta suspendida, si crees que esto es un error, envia un correo eletronico ha schoolnotes.info@gmail.com.";
                $data['code'] = 421;
            }else{
                if($user->correo_verificado){
                    $data['code'] = 200;
                    $data['mensaje'] = 'Este usuario esta verificado';
                }else{
                    $data['mensaje'] = "Lo sentimos $user->name, pero aun no has verificado tu correo electronico, hemos enviado un correo electronico a $user->email para que verifiques tu cuenta.";
                    $data['code'] = 421;
                    //Envia correo para verificar el correo electronico
                    $this->verificarCorreo($user);
                }
            }
        }else{
            $data['mensaje'] = 'Oops... no pudimos localizar tu cuenta... vuelve a intentarlo.';
            $data['code'] = 404;
        }
        return $data;
    }

    public function userActivo($user){
        $code = $user ? true : false;
        if($code){
            if($user->activo == 1){
                $data['mensaje'] = 'Usuario Encontrado';
                $data['code'] = 200;
            }else{
                $data['mensaje'] = 'Al usuario que intentas seguir, esta suspendido.';
                $data['code'] = 421;
            }
        }else{
            $data['mensaje'] = 'Oops... no pudimos localizar tu cuenta... vuelve a intentarlo.';
            $data['code'] = 404;
        }
        return $data;
    }

    public function publicacionActivo($publicacion){
        $code = $publicacion ? true : false;
        if($code){
            if($publicacion->activo == 1){
                $data['mensaje'] = 'Publicacion Encontrada';
                $data['code'] = 200;
            }else{
                $data['mensaje'] = 'La publicacion ha sido borrada.';
                $data['code'] = 421;
            }
        }else{
            $data['mensaje'] = 'Oops... no pudimos localizar la publicacion... vuelve a intentarlo.';
            $data['code'] = 404;
        }
        return $data;
    }

    public function comentarioActivo($comentario){
        $code = $comentario ? true : false;
        if($code){
            $data['mensaje'] = 'Comentario Encontrado';
            $data['code'] = 200;
        }else{
            $data['mensaje'] = 'Oops... no pudimos localizar el comentario o no existe... vuelve a intentarlo.';
            $data['code'] = 404;
        }
        return $data;
    }

    public function apunteActivo($apunte){
        $code = $apunte ? true : false;
        if($code){
            if($apunte->activo == 1){
                $data['mensaje'] = 'Apunte Encontrado';
                $data['code'] = 200;
            }else{
                $data['mensaje'] = 'El apunte ha sido borrada.';
                $data['code'] = 421;
            }
        }else{
            $data['mensaje'] = 'Oops... no pudimos localizar el apunte... vuelve a intentarlo.';
            $data['code'] = 404;
        }
        return $data;
    }

    public function datosUser($data){
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        return $validator;
    }

    public function datosApunte($data){
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
        ]);
        return $validator;
    }

    public function datosCategoria($data){
        $validator = Validator::make($data, [
            'nombre' => 'required|string|max:255',
        ]);
        return $validator;
    }

    public function datosComentario($data){
        $validator = Validator::make($data, [
            'comentario' => 'required',
            'user_id' => 'required',
        ]);
        return $validator;
    }

    public function datosEtiqueta($data){
        $validator = Validator::make($data, [
            'nombre' => 'required|string',
            'slug' => 'required|string|unique:etiquetas',
        ]);
        return $validator;
    }

    public function datosPregunta($data){
        $validator = Validator::make($data, [
            'contenido' => 'required', 
            'user_id' => 'required',
        ]);
        return $validator;
    }

    public function datosPublicacion($data){
        $validator = Validator::make($data, [
            'contenido' => 'required|string',
            'user_id' => 'required|string',
        ]);
        return $validator;
    }

    public function datosRespuesta($data){
        $validator = Validator::make($data, [
            'contenido' => 'required', 
            'user_id' => 'required',
            'publicacion_id' => 'required',
        ]);
        return $validator;
    }
}