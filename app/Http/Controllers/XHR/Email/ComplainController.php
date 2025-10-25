<?php

namespace App\Http\Controllers\XHR\Email;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Email\ComplainRequest;
use App\Mail\Complain;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ComplainController extends Controller
{
    /**
     * Handle notice of complaint send
     *
     * @param ComplainRequest $request
     * @return JsonResponse
     */
    public function __invoke(
        ComplainRequest $request
    ): JsonResponse
    {
        $data = $request->validated();
        $message = $data['complain']['message'] ? strip_tags($data['complain']['message']) : __('base.Empty_value');
        $adminEmail = consumerBuilder('admin')->first('email')->email;

        Mail::to($adminEmail ?? env('MAIL_TO_ADDRESS'))->send(new Complain([
            'email' => $data['email'] ?? __('base.Empty_value'),
            'phone' => $data['phone'] ?? __('base.Empty_value'),
            'url' => $data['complain']['url'],
            'theme' => __("component.complain.theme.{$data['complain']['theme']}"),
            'message' => $message,
        ]));

        return response()->json([
            'ok' => true,
            'message' => __('component.complain.ok'),
        ]);
    }
}
