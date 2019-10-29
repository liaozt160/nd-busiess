<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BusinessForSaleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $file;
    public $email = 'tank@ylbservices.com';
    public $subject = 'Business for sale';
    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($file,$email)
    {
        $this->file = $file;
        $this->email=$email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(env('APP_ENV') == 'production'){
            $this->to(config('config.karen.email'),config('config.karen.name'));
        }
        return $this->subject($this->subject)
            ->bcc(config('config.tank.email'))
            ->attach(storage_path('app/temp/'.$this->file))
            ->view('emails.business_for_sale');
    }
}
