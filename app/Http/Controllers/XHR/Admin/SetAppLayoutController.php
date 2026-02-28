<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Admin\SetAppLayoutRequest;
use Illuminate\Http\JsonResponse;

class SetAppLayoutController extends Controller
{
    /**
     * Update app layout setting
     *
     * @param SetAppLayoutRequest $request
     * @return JsonResponse
     */
    public function __invoke(
        SetAppLayoutRequest $request
    ): JsonResponse
    {
        return transaction(function () use ($request) {
            $validated = $request->validated();
            $appSettings = appSettings();

            // Update App layout setting
            $appSettings->set("layout.default.{$validated['material_type']}", $validated['settings']);

            // Update layouts of existent materials
            if ($validated['opt'] === 'existent_and_new') {
                materialBuilder($validated['material_type'])->update(['layout' => $validated['settings']]);
            }

            // Response
            return response()->json([
                'ok' => true,
                'newSettings' => $appSettings->get(),
                'message' => __('base.Changes_saved')
            ]);
        });
    }
}
