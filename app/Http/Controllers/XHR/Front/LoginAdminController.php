<?php

namespace App\Http\Controllers\XHR\Front;

use App\Actions\LoginAdminAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Front\LoginAdminRequest;
use Illuminate\Http\JsonResponse;

class LoginAdminController extends Controller
{
    /**
     * Handle of administrator or moderator login
     *
     * @param LoginAdminRequest $request
     * @param LoginAdminAction $action
     * @return JsonResponse
     */
    public function __invoke(
        LoginAdminRequest $request,
        LoginAdminAction  $action
    ): JsonResponse
    {
        $validated = $request->validated();
        $responseData = $action->handle($validated);

        return response()->json($responseData);
    }
}
