<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Admin\SetAppSettingsRequest;
use Illuminate\Http\JsonResponse;

class SetAppSettingsController extends Controller
{
    /**
     * Update app setting
     *
     * @param SetAppSettingsRequest $request
     * @return JsonResponse
     */
    public function __invoke(
        SetAppSettingsRequest $request
    ): JsonResponse
    {
        $validated = $request->validated();
        $appSettings = appSettings();

        // Update setting
        $appSettings->set($validated['dotTargetKey'], $validated['value']);

        // Response
        return response()->json([
            'ok' => true,
            'newSettings' => $appSettings->get(),
            'message' => __('base.Changes_saved')
        ]);
    }
}
