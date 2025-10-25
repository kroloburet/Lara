<?php

namespace App\Http\Controllers\XHR;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\SetConsumerSettingsRequest;
use Illuminate\Http\JsonResponse;

class SetConsumerSettingsController extends Controller
{
    /**
     * Update consumer settings
     *
     * @param SetConsumerSettingsRequest $request
     * @return JsonResponse
     */
    public function __invoke(
        SetConsumerSettingsRequest $request
    ): JsonResponse
    {
        $validated = $request->validated();
        $consumerSettings = consumerSettings($validated['consumerType']);

        // Update setting
        $consumerSettings->set($validated['dotTargetKey'], $validated['value']);

        // Response
        return response()->json([
            'ok' => true,
            'newSettings' => $consumerSettings->get(),
            'message' => __('base.Changes_saved')
        ]);
    }
}
