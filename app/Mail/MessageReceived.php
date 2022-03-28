<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Hoja de Detalle de Consumo';
    public $prueba;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($prueba)
    {
        $this->prueba = $prueba;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.messageReceived');
    }
}