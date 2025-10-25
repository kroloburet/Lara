<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Admin\UpdateOrCreateMaterialRequest;
use App\Services\SitemapService;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;

class UpdateOrCreateMaterialController extends Controller
{
    /**
     * Handle of create or update material
     *
     * @param UpdateOrCreateMaterialRequest $request
     * @return JsonResponse
     */
    public function __invoke
    (
        UpdateOrCreateMaterialRequest $request
    ): JsonResponse
    {
        $this->authorize('permits', ['material', 'uc', 'or']);

        $validated = $request->validated();

        transaction(function () use ($validated) {
            $isStatic = config("app.materials.types.{$validated['type']}.static");
            $material = materialBuilder($validated['type']);

            // Static material can only be updated
            if ($isStatic) {
                $material = $material->first();
                $material->update($validated);
                $isCreating = false;
            } else {
                $isCreating = !$material->firstWhere('alias', $validated['alias']);
                $material = $material->updateOrCreate(
                    ['alias' => $validated['alias']],
                    $validated
                );
            }

            // Upload bg image if given
            if ($material && !empty($validated['bg_image'])) {
                $service = app(UploadService::class);
                $service->bgImageUpload($validated['bg_image'], $material->storage);
            }

            // Update or Create material lang version content fields
            $material->contents()->updateOrCreate(
                ['locale' => $validated['locale']],
                $validated
            );

            // Sitemap refresh
            if (appSettings('sitemap.refresh') === 'auto') {
                app(SitemapService::class)->writeSitemap();
            }

            // Push log event
            $stage = $isCreating ? 'created' : 'updated';
            $alias = $material->alias ?? $material->id;
            $currentConsumer = auth()->user();
            $currentConsumer->pushLogEvent("Material [{$material->type}:{$alias}] {$stage}");
        });

        return response()->json([
            'message' => __('base.Changes_saved'),
        ]);
    }
}
