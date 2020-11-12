<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApunteAviso extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $imagenes;
    public $apunte;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $imagenes, $apunte)
    {
        $this->data = $data;
        $this->imagenes = $imagenes;
        $this->apunte = $apunte;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.avisos_apunte')
        ->subject($this->data['name'].' ha creado un nuevo Apunte')
        ->from('schoolnotes.info@gmail.com');
    }
}
