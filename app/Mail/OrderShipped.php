<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderShipped extends Mailable
{
  use Queueable, SerializesModels;

  public $m3_email;

  public function __construct($m3_email)
  {
    $this->m3_email = $m3_email;
  }

  public function build()
  {
    return $this->view('email_register');
  }
}
