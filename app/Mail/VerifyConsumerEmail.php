<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyConsumerEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $signedUrl
    )
    {
        //
    }

    /**
     * The message builder.
     */
    public function build()
    {
        return $this->view('components.layouts.email')
            ->subject(__('email.verify_email.subject'))
            ->with([
                'subject' => __('email.verify_email.subject'),
                'body' => __('email.verify_email.body', ['signedUrl' => $this->signedUrl])
            ]);
    }
}
