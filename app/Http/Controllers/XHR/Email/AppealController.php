<?php

namespace App\Http\Controllers\XHR\Email;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Email\AppealRequest;
use App\Mail\Appeal;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class AppealController extends Controller
{
    /**
     * Handle send notice of appeal
     *
     * @param AppealRequest $request
     * @return JsonResponse
     */
    public function __invoke(
        AppealRequest $request
    ): JsonResponse
    {
        $data = $request->validated();

        Mail::to(env('MAIL_TO_ADDRESS'))->send(new Appeal([
            'email' => $data['email'] ?? __('base.Empty_value'),
            'phone' => $data['phone'] ?? __('base.Empty_value'),
            'theme' => __("form.appeal.theme.{$data['appeal']['theme']}"),
            'message' => $data['appeal']['message'],
        ]));

        return response()->json([
            'message' => __('form.appeal.ok'),
        ]);
    }
}
