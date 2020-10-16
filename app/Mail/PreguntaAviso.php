<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreguntaAviso extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $imagenes;
    public $pregunta;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $imagenes, $pregunta)
    {
        $this->data = $data;
        $this->imagenes = $imagenes;
        $this->pregunta = $pregunta;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.avisos')
        ->subject($this->data['name'].' ha creado una pregunta')
        ->from('schoolnotes.info@gmail.com');
    }
}
