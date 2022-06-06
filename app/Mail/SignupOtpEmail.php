<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SignupOtpEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'care@orangebakery.in';
        $name = 'OrangeBakery';
        $fullname = $this->data['name'];
        $otp = $this->data['otp'];
        $subject = "Signup - Orange Bakery";
        
        return $this->view('mail.otp',compact('fullname','otp'))
                    ->from($address, $name)
                    ->replyTo($address, $name)
                    ->subject($subject);
    }
}
