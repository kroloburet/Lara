<?php

namespace App\Http\Controllers\XHR\Front;

use App\Actions\PaginateFilteredMaterialsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\PaginateFilteredMaterialsRequest;
use Illuminate\Http\JsonResponse;

class PaginateMaterialsListController extends Controller
{
    /**
     * Get HTML data for paginator of materials
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
        $validate = $request->validated();
        $materials = $action->handle($validate);

        return response()->json([
            'ok' => $materials->isNotEmpty(),
            'html' => view(
                'components.layouts.front.material-list',
                compact('materials')
            )->render()
        ]);
    }
}
