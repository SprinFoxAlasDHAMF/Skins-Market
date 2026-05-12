<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositoConfirmado extends Mailable
{
    use Queueable, SerializesModels;

    public $monto;
    public $saldo;

    public function __construct($monto, $saldo)
    {
        $this->monto = $monto;
        $this->saldo = $saldo;
    }

    public function build()
    {
        return $this->subject('Depósito confirmado ✔')
            ->view('emails.deposito-confirmado');
    }
}