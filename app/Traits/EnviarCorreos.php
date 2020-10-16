<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;

use App\Mail\VerificarCorreo;
use App\Mail\RecuperarContrasena;
use App\Mail\WarningMail;
use App\Mail\Banned;
use App\Mail\PreguntaAviso;
use App\Traits\Imagenes;
use App\User;

trait EnviarCorreos {
    use Imagenes;

    public function verificarCorreo($data){
        //Obtengo URL de las imagenes
        $imagenes = $this->ImagenesSchoolNotes();
        Mail::to($data->email)->send(new VerificarCorreo($data,$imagenes));
    }

    public function recuperarContrasena($data){
        //Obtengo URL de las imagenes
        $imagenes = $this->ImagenesSchoolNotes();
        Mail::to($data->email)->send(new RecuperarContrasena($data,$imagenes));
    }

    public function enviarWarning($data, $warning){
        //Obtengo URL de las imagenes
        $imagenes = $this->ImagenesSchoolNotes();        
        Mail::to($data->email)->send(new WarningMail($data,$imagenes,$warning));

    }
    public function cuentaBaneada($data){
        //Obtengo URL de las imagenes
        $imagenes = $this->ImagenesSchoolNotes();        
        Mail::to($data->email)->send(new Banned($data,$imagenes));
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
        Mail::to($emails)->send(new PreguntaAviso($user,$imagenes,$publicacion));
    }

}
