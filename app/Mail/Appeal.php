<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Appeal extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public array $bodyVars)
    {
        //
    }

    /**
     * The message builder.
     */
    public function build()
    {
        return $this->view('components.layouts.email')
            ->subject(__('email.appeal.subject'))
            ->with([
                'subject' => __('email.appeal.subject'),
                'body' => __('email.appeal.body', $this->bodyVars)
            ]);
    }
}
