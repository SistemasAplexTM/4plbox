<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WarehouseEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $param;
    public $from_self;
    public $subject_msn;
    protected $pdf;
    protected $pdf_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($param, $pdf, $from_self = array('address' => 'sac@4plbox.com', 'name' => '4plbox'), $subject_msn = 'Mensaje 4plbox')
    {
        $this->param = $param;
        $this->from_self = $from_self;
        $this->subject_msn = $subject_msn;
        if($pdf){
            $this->pdf = base64_encode($pdf['pdf']);
            $this->pdf_name = $pdf['pdf_name'];
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->pdf){
            return $this->from($this->from_self['address'])
            ->subject($this->subject_msn)
            ->view('emailTemplate')
            ->attachData(base64_decode($this->pdf), $this->pdf_name.'.pdf', [
                    'mime' => 'application/pdf',
            ]);
        }else{
            return $this->from($this->from_self)
            ->subject($this->subject_msn)
            ->view('emailTemplate');
        }

    }
}
