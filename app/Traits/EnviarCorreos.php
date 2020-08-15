<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;

use App\Mail\VerificarCorreo;
use App\Mail\RecuperarContrasena;
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
}
