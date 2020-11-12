<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificarRespuesta extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $imagenes;
    public $pregunta;
    public $respuesta;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $imagenes, $pregunta, $respuesta)
    {
        $this->data = $data;
        $this->imagenes = $imagenes;
        $this->pregunta = $pregunta;
        $this->respuesta = $respuesta;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.notificar_respuesta')
        ->subject($this->data['name'].', Tienes una respuesta nueva!')
        ->from('schoolnotes.info@gmail.com');
    }
}
