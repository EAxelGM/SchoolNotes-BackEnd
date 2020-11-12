<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;

use App\Mail\VerificarCorreo;
use App\Mail\RecuperarContrasena;
use App\Mail\WarningMail;
use App\Mail\Banned;
use App\Mail\PreguntaAviso;
use App\Mail\ApunteAviso;
use App\Mail\NotificarRespuesta;
use App\Mail\NotificarMultiploClips;
use App\Traits\Imagenes;
use App\User;
use App\Pregunta;
use App\CodigoCreador as Codigo;

trait EnviarCorreos {
    use Imagenes;

    public function verificarCorreo($data){
        //Obtengo URL de las imagenes
        $imagenes = $this->ImagenesSchoolNotes();
        Mail::bcc($data->email)->send(new VerificarCorreo($data,$imagenes));
    }

    public function recuperarContrasena($data){
        //Obtengo URL de las imagenes
        $imagenes = $this->ImagenesSchoolNotes();
        Mail::bcc($data->email)->send(new RecuperarContrasena($data,$imagenes));
    }

    public function enviarWarning($data, $warning){
        //Obtengo URL de las imagenes
        $imagenes = $this->ImagenesSchoolNotes();
        Mail::bcc($data->email)->send(new WarningMail($data,$imagenes,$warning));

    }
    public function cuentaBaneada($data){
        //Obtengo URL de las imagenes
        $imagenes = $this->ImagenesSchoolNotes();
        Mail::bcc($data->email)->send(new Banned($data,$imagenes));
    }

    public function getCorreos($arrayID){
        $emails = [];
        foreach($arrayID as $id){
            $usuario = User::where([
                ['_id', $id],
                ['activo', 1],
            ])->first();
            if($usuario){
                array_push($emails,$usuario->email);
            }
        }
        return $emails;
    }

    public function avisaSeguidoresPregunta($user, $publicacion){
        //Obtener los Emails de los seuidores
        $emails = $this->getCorreos($user->seguidores);
        //Obtengo URL de las imagenes
        $imagenes = $this->ImagenesSchoolNotes();
        Mail::bcc($emails)->send(new PreguntaAviso($user,$imagenes,$publicacion));
    }

    public function notificarRespuesta($user, $respuesta){
        $pregunta = Pregunta::find($respuesta->pregunta_id);
        if($pregunta){
          $user_propietario = User::find($pregunta->user_id);
          if($user_propietario){
            if($user_propietario->_id != $user->_id){
              $imagenes = $this->ImagenesSchoolNotes();
              Mail::bcc($user_propietario->email)->send(new NotificarRespuesta($user_propietario,$imagenes,$pregunta, $respuesta));
            }
          }
        }
    }

    public function notificarApunteNuevo($apunte){
      $user = User::find($apunte->user_id);
      if($user){
        $emails = $this->getCorreos($user->seguidores);
        if(count($emails) != 0){
          //Obtengo URL de las imagenes
          $imagenes = $this->ImagenesSchoolNotes();
          Mail::bcc($emails)->send(new ApunteAviso($user,$imagenes,$apunte));
        }
      }
    }

    public function notificaMultiploClips($user){
      $imagenes = $this->ImagenesSchoolNotes();
      $codigo = Codigo::where([['user_id', $user->_id],['activo', 1]])->first();
      $user['codigo_creador'] = $codigo;
      Mail::bcc($user->email)->send(new NotificarMultiploClips($user,$imagenes));
    }
}
