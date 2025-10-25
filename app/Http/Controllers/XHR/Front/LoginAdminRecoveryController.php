<?php

namespace App\Http\Controllers\XHR\Front;

use App\Actions\LoginAdminRecoveryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Front\LoginAdminRecoveryRequest;
use App\Services\RecoveryService;
use Illuminate\Http\JsonResponse;

class LoginAdminRecoveryController extends Controller
{
    /**
     * Handle recovery login data of administrator or moderator
     *
     * @param LoginAdminRecoveryRequest $request
     * @param LoginAdminRecoveryAction $action
     * @return JsonResponse
     */
    public function __invoke(
        LoginAdminRecoveryRequest $request,
        LoginAdminRecoveryAction  $action
    ): JsonResponse
    {
        $validated = $request->validated();
        $responseData = $action->handle($validated);

        return response()->json($responseData);
    }
}
