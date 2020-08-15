<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificarCorreo extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $imagenes;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$imagenes)
    {
        $this->data = $data;
        $this->imagenes = $imagenes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.verificarCorreo')
        //return $this->view('mails.verificarCorreo')
        ->subject('Verificacion de Correo SchoolNotes')
        ->from('schoolnotes.info@gmail.com');
    }
}
