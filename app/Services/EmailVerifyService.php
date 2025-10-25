<?php

namespace App\Services;

use App\Mail\VerifyConsumerEmail;
use App\Models\Abstract\Consumer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class EmailVerifyService
{
    /**
     * Send a request for email confirmation to the consumer's email
     *
     * @param Consumer $consumer
     * @param string $email
     * @return bool
     */
    public function sendVerifyEmailNotice(
        Consumer $consumer,
        string   $email,
    ): bool
    {
        if (! $consumer || ! $email) {
            return false;
        }

        // Rewrite verify email token if not exist
        if ($consumer->isVerifiedEmail()) {
            $this->setEmailAsUnverified($consumer);
        }

        // Send
        $signedUrl = URL::signedRoute('verify.email.execute',
            ['type' => $consumer->type, 'token' => $consumer->verify_email_token]
        );
        return (bool) Mail::to($email)
            ->send(new VerifyConsumerEmail($signedUrl));
    }

    /**
     * Mark the consumer's email as verified
     * (Remove the generated token in verify_email_token)
     *
     * @param Consumer $consumer
     * @return bool
     */
    public function setEmailAsVerified(
        Consumer $consumer
    ): bool
    {
        try {
            // Delete token
            $consumer->verify_email_token = null;
            $consumer->save();

            // Push log event
            $consumer->pushLogEvent("Email [{$consumer->email}] verified");

            return true;
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Mark the consumer's email as not verified
     * (Save the generated token in verify_email_token)
     *
     * @param Consumer $consumer
     * @return bool
     */
    public function setEmailAsUnverified(
        Consumer $consumer
    ): bool
    {
        return transaction(function () use ($consumer) {
            $consumer->verify_email_token = (string) Str::uuid();
            return $consumer->save();
        });
    }


}
