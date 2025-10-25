<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Recovery;
use Illuminate\Http\RedirectResponse;

class LoginRecoveryController extends Controller
{
    /**
     * Handle check and redirect to security updating page
     *
     * @param string $token Token of recovery
     * @return RedirectResponse
     */
    public function __invoke(string $token): RedirectResponse
    {
        $recoveryRecord = Recovery::where('token', $token);
        $consumer = $recoveryRecord->first()?->consumer;

        abort_if(! $consumer, 404);

        $type = $consumer->profile ? 'profile' : 'admin';

        $recoveryRecord->delete();

        $type === 'profile'
            ? profileAuth()->login($consumer)
            : auth('admin')->login($consumer);

        return redirect()->route("{$type}.update.security");
    }
}
