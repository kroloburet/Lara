<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Services\EmailVerifyService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Handle check verified email
     *
     * @param string $type Type of consumer
     * @param string $token Verify email token
     * @param EmailVerifyService $verifyService
     * @return View|RedirectResponse
     */
    public function __invoke(
        string $type,
        string $token,
        EmailVerifyService $verifyService
    ): View|RedirectResponse
    {
        abort_if(!$type || !$token, 403);

        $consumer = getProfileModel($type, false)
            ->firstWhere('verify_email_token', $token);

        if (! $profile) return view('verify.verify-email-abort');

        $verifyService->setEmailAsVerified($profile);

        return redirect()->route('verify.email.verified');
    }
}
