<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->with([
                'name'            => $this->details['name'],
                'main_title_text' => $this->details['welcome_email_title'],
                'app_local'       => app()->getLocale(),
            ])
            ->subject($this->details['user_welcome_email_subject'])
            ->view('emails.welcomeUser');
    }
}
