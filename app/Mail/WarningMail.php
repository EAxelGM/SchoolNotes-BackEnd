<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WarningMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $imagenes;
    public $warning;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $imagenes, $warning)
    {
        $this->data = $data;
        $this->imagenes = $imagenes;
        $this->warning = $warning;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.warnings')
        ->subject('Has recibido un warning')
        ->from('schoolnotes.info@gmail.com');
    }
}
