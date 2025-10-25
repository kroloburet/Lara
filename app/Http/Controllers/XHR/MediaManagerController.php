<?php

namespace App\Http\Controllers\XHR;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\MediaManagerRequest;
use Illuminate\Http\JsonResponse;

class MediaManagerController extends Controller
{
    /**
     * Handle set material media
     *
     * @param MediaManagerRequest $request
     * @return JsonResponse
     */
    public function set(
        MediaManagerRequest $request
    ): JsonResponse
    {
        $validated = $request->validated();
        $material = $request->material;
        $result = materialMedia($material, $validated['media']['path'])->set($validated['media']);

        if ($result !== false) {
            return response()->json([
                'ok' => true,
                'files' => $result, // Send the final file list back to the client
            ]);
        }

        return response()->json(['ok' => false], 422);
    }

    /**
     * Handle for renaming a media file.
     *
     * @param MediaManagerRequest $request
     * @return JsonResponse
     */
    public function rename(MediaManagerRequest $request): JsonResponse
    {
        $validated = $request->validated()['media'];
        $material = $request->material;

        $result = materialMedia($material, $validated['path'])->rename(
            $validated['id'],
            $validated['old_name'],
            $validated['new_name']
        );

        if ($result !== false) {
            // On success, return a success response and the updated file list
            return response()->json([
                'ok' => true,
                'files' => $result,
            ]);
        }

        // On failure, return an error response
        return response()->json([
            'ok' => false,
            'message' => __('component.media_selector.rename_error'),
        ], 422);
    }
}
