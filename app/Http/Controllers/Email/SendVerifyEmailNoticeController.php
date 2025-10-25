<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Services\EmailVerifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SendVerifyEmailNoticeController extends Controller
{
    /**
     * Handle send email verify notice
     *
     * @param Request $request
     * @param EmailVerifyService $verifyService
     * @return JsonResponse
     */
    public function __invoke(
        Request $request,
        EmailVerifyService $verifyService
    ): JsonResponse
    {
        $currentConsumer = auth()->user();
        $email = $currentConsumer->email;
        $isSent = $verifyService->sendVerifyEmailNotice($currentConsumer, $email);

        return $isSent
            ? response()
                ->json(['ok' => true, 'message' => __('verify.email.resend_response_ok')])
            : response()
                ->json(['ok' => false, 'message' => __('verify.email.resend_response_err')]);
    }
}
