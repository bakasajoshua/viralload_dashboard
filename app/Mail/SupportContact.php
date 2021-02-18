<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportContact extends Mailable
{
    use Queueable, SerializesModels;

    private $subject_message = '';
    public $body_message = '';
    public $from_name = '';
    public $from_email = '';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($from_name, $from_email, $subject, $message)
    {
        $this->subject_message = $subject;
        $this->body_message = $message;
        $this->from_name = $from_name;
        $this->from_email = $from_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject_message)
                ->from($this->from_email, $this->from_name)
                ->view('mail.contactus');
    }
}
