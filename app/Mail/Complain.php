<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Complain extends Mailable
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
            ->subject(__('email.complain.subject'))
            ->with([
                'subject' => __('email.complain.subject'),
                'body' => __('email.complain.body', $this->bodyVars)
            ]);
    }
}
