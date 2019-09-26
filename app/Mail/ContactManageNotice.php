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
        return $this->subject($this->subject)
            ->to('tank@ylbservices.com','tank')
            ->to('liaozt160@qq.com','liao')
            ->bcc('jason@ylbservices.com','tank')
            ->view('emails.ContactManageNotice');
    }

}
