<?php

namespace App\Services;

use App\Mail\RecoveryConsumer;
use App\Models\Abstract\Consumer;
use App\Models\Recovery;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class RecoveryService
{
    public function sendRecoveryLoginNotice(Consumer $consumer): bool
    {
        // Get first or create record
        $token = (string) Str::uuid();
        $recoveryRecord = Recovery::firstOrCreate([
            'email' => $consumer->email,
            'consumer_type' => $consumer->type,
            'consumer_id' => $consumer->id,
        ],
        [
            'token' => $token,
            'created_at' => now(),
        ]);

        if (! $recoveryRecord) return false;

        $signedUrl = URL::signedRoute('recovery.execute', ['token' => $recoveryRecord->token]);

        // Send notice
        return (bool) Mail::to($recoveryRecord->email)->send(new RecoveryConsumer($signedUrl));
    }
}
