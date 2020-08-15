<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;

trait Imagenes {

    public function ImagenesSchoolNotes(){
        $imagen['large'] = asset('assets/SchoolNoteslarge.png');
        $imagen['book'] = asset('assets/Book.png');
        $imagen['icono'] = asset('assets/Icono_SN_blanco.png');
        $imagen[0] = asset('assets/Fondo_bienvenida.png');
        return $imagen;
    }
}
