<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Actions\GetMaterialContentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Admin\GetMaterialContentRequest;
use Illuminate\Http\JsonResponse;

class GetMaterialContentController extends Controller
{
    /**
     * Get material localize content if exist
     *
     * @param GetMaterialContentRequest $request
     * @param GetMaterialContentAction $action
     * @return JsonResponse
     */
    public function __invoke(
        GetMaterialContentRequest $request,
        GetMaterialContentAction $action
    ): JsonResponse
    {
        $validated = $request->validated();
        $responseData = $action->handle($validated);

        return response()->json($responseData);
    }
}
