<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactManageNotice extends Mailable
{
    use Queueable, SerializesModels;
    public $contact;
    public $subject = "newdream contact us notice";
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(env('APP_ENV') == 'production'){
            $this->to(config('config.victor.email'),config('config.victor.name'))
                ->to(config('config.karen.email'),config('config.karen.name'));
        }
        return $this->subject($this->subject)
            ->bcc(config('config.tank.email'),config('config.tank.name'))
            ->view('emails.ContactManageNotice');
    }
}
