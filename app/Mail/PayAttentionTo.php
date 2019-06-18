<?php

namespace App\Mail;

use App\Models\Business;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PayAttentionTo extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Business Attention';
    public $business;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Business $business,$email)
    {
        $this->business  = $business;
        $this->email  = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->to($this->email)->view('emails.pay_attention_to');
    }
}
