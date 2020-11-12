<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificarMultiploClips extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $imagenes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $imagenes)
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
        return $this->view('mails.notificar_multiplo_clips')
        ->subject('¡Felicidades '.$this->data['name'].' has llegado a los '. $this->data['clips'].' clips!')
        ->from('schoolnotes.info@gmail.com');
    }
}
