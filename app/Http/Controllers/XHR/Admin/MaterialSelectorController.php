<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Actions\PaginateFilteredMaterialsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\PaginateFilteredMaterialsRequest;
use Illuminate\Http\JsonResponse;

class MaterialSelectorController extends Controller
{
    /**
     * Get HTML data of material-selector-component
     *
     * @return JsonResponse
     */
    public function loadComponent(): JsonResponse
    {
        $locale = request('locale', app()->getLocale());

        return response()->json([
            'html' => view('components.admin.material-selector-component', compact('locale'))->render()
        ]);
    }

    /**
     * Get HTML data for material-selector-component
     *
     * @param PaginateFilteredMaterialsRequest $request
     * @param PaginateFilteredMaterialsAction $action
     * @return JsonResponse
     */
    public function getList(
        PaginateFilteredMaterialsRequest $request,
        PaginateFilteredMaterialsAction $action
    ): JsonResponse
    {
        $validated = $request->validated();
        $materials = $action->handle($validated);

        return response()->json([
            'html' => view(
                'components.layouts.admin.material-selector-list',
                compact('materials')
            )->render()
        ]);
    }
}
