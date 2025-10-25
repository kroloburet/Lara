<?php

namespace App\Http\Controllers\XHR;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\BgImageManagerRequest;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;

class BgImageManagerController extends Controller
{
    /**
     * Handle of upload material bg image.
     *
     * @param BgImageManagerRequest $request
     * @param UploadService $service
     * @return JsonResponse
     */
    public function upload(
        BgImageManagerRequest $request,
        UploadService $service
    ): JsonResponse
    {
        $validated = $request->validated();
        $material = $request->material;
        $url = $service->bgImageUpload($validated['bg_image'], $material->storage);

        return response()->json([
            'url' => $url,
        ]);
    }

    /**
     * Handle of delete material bg image.
     *
     * @param BgImageManagerRequest $request
     * @param UploadService $service
     * @return JsonResponse
     */
    public function delete(
        BgImageManagerRequest $request,
        UploadService $service
    ): JsonResponse
    {
        $material = $request->material;
        $url = $service->bgImageDelete($material->storage);

        return response()->json([
            'url' => $url,
        ]);
    }
}
