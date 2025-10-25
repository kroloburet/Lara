<?php

namespace App\Http\Controllers\XHR\Front;

use App\Actions\PaginateFilteredMaterialsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\PaginateFilteredMaterialsRequest;
use Illuminate\Http\JsonResponse;

class PaginateSubMaterialsListController extends Controller
{
    /**
     * Get HTML data for paginator of sub materials
     *
     * @param PaginateFilteredMaterialsRequest $request
     * @param PaginateFilteredMaterialsAction $action
     * @return JsonResponse
     */
    public function __invoke(
        PaginateFilteredMaterialsRequest $request,
        PaginateFilteredMaterialsAction $action
    ): JsonResponse
    {
        $validated = $request->validated();
        $materials = $action->handle($validated);

        return response()->json([
            'ok' => !$materials->isEmpty(),
            'html' => view(
                'components.layouts.front.material-list',
                compact('materials')
            )->render()
        ]);
    }
}
