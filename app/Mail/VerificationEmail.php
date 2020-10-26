<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
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
                'app_local'       => app()->getLocale(),
                'actionUrl'       => $this->details['actionUrl'],
                'main_title_text' => $this->details['main_title_text'],
            ])
            ->subject($this->details['verification_email_subject'])
            ->view('emails.verification');
    }
}
