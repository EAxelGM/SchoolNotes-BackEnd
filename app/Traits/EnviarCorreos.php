<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;

use App\Mail\VerificarCorreo;
use App\Mail\RecuperarContrasena;
use App\Mail\WarningMail;
use App\Mail\Banned;
use App\Traits\Imagenes;

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
}
