<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Admin\ToggleBlockOrDeleteMaterialRequest;
use App\Models\Abstract\Material;
use App\Services\SitemapService;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;

class DeleteMaterialController extends Controller
{
    /**
     * Handle of material deleted
     *
     * @param ToggleBlockOrDeleteMaterialRequest $request
     * @return JsonResponse
     */
    public function __invoke(
        ToggleBlockOrDeleteMaterialRequest $request
    ): JsonResponse
    {
        $this->authorize('permits', ['material', 'd']);

        $validated = $request->validated();

        /** @var Material $material */
        $material = materialBuilder($validated['type'])->firstWhere(['alias' => $validated['alias']]);

        transaction(function () use ($validated, $material) {
            $uploadService = app(UploadService::class);

            $this->deleteMaterialRecursively($material, $uploadService);

            // Sitemap refresh
            if (appSettings('sitemap.refresh') === 'auto') {
                app(SitemapService::class)->writeSitemap();
            }

            // Push log event
            $currentConsumer = auth()->user();
            $currentConsumer->pushLogEvent("Material [{$validated['type']}:{$validated['alias']}] deleted");
        });

        return response()->json([
            'ok' => true,
            'message' => __("admin.material.del_done"),
        ]);
    }

    /**
     * Recursively deletes a material and its nested materials (subcategories and pages) across non-static material models.
     *
     * @param Material $material The material to delete.
     * @param UploadService $uploadService The service for removing material storage.
     * @return void
     */
    private function deleteMaterialRecursively(Material $material, UploadService $uploadService): void
    {
        if ($material->type === 'category') {
            // Get non-static material types from config
            $nonStaticTypes = collect(config('app.materials.types', []))
                ->filter(fn($typeConfig) => !($typeConfig['static'] ?? true))
                ->pluck('model');

            // Query all non-static models for nested materials
            foreach ($nonStaticTypes as $modelClass) {
                $nestedMaterials = $modelClass::where('category_id', $material->id)->cursor();
                foreach ($nestedMaterials as $nestedMaterial) {
                    $this->deleteMaterialRecursively($nestedMaterial, $uploadService);
                }
            }
        }

        $uploadService->removeMaterialStorage($material->storage);
        $material->contents()->forceDelete();
        $material->forceDelete();
    }
}
