<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Admin\ToggleBlockOrDeleteMaterialRequest;
use App\Services\SitemapService;
use Illuminate\Http\JsonResponse;

class ToggleBlockMaterialController extends Controller
{
    /**
     * Handle toggle block of material
     *
     * @param ToggleBlockOrDeleteMaterialRequest $request
     * @return JsonResponse
     */
    public function __invoke(
        ToggleBlockOrDeleteMaterialRequest $request
    ): JsonResponse
    {
        $this->authorize('permits', ['material', 'u']);

        $validated = $request->validated();
        $material = materialBuilder($validated['type'])->withTrashed()->firstWhere(['alias' => $validated['alias']]);
        $alias = $material->alias ?? $material->id;

        $material->isBlocked()
            ? $material->unblock()
            : $material->block();

        // Sitemap refresh
        if (appSettings('sitemap.refresh') === 'auto') {
            app(SitemapService::class)->writeSitemap();
        }

        // Push log event
        $stage = !$material->isBlocked() ? 'published' : 'unpublished';
        $currentConsumer = auth()->user();
        $currentConsumer->pushLogEvent("Material [{$material->type}:{$alias}] {$stage}");

        return response()->json([
            'ok' => true,
            'message' => $material->isBlocked()
                ? __("admin.material.unpublic_done")
                : __("admin.material.public_done"),
        ]);
    }
}
