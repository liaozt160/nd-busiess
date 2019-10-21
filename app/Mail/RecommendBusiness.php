<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecommendBusiness extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $subject = 'Business Recommend';
//    public $disk = 'temp';
//    public $fromName ='Tank';
//    public $toName = 'test';
    public $file;
    public $email = 'tank@ylbservices.com';
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
        return $this->subject($this->subject)->to($this->email)
            ->bcc(config('config.tank.email'))
            ->attach(storage_path('app/temp/'.$this->file))
            ->view('emails.recommend_business');
    }
}
