<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sichikawa\LaravelSendgridDriver\SendGrid;

class BodegaRecibido extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $param;
    public $from_name;
    public $from_email;
    public $subject_msn;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($param, $from_self = array('address' => 'sac@4plbox.com', 'name' => '4plbox'), $subject_msn = 'Nuevo mensaje')
    {
        $this->param = $param;
        $this->from_name = $from_self['name'];
        $this->from_email = $from_self['address'];
        $this->subject_msn = $subject_msn;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      // return $this->view('emailTemplate');
      return $this->view('emailTemplate')
      ->from($this->from_email, $this->from_name)
      ->subject($this->subject_msn);
    }
}
