<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Actions\PaginateFilteredMaterialsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\PaginateFilteredMaterialsRequest;
use Illuminate\Http\JsonResponse;

class PaginateFilteredMaterialsListController extends Controller
{
    /**
     * Get HTML data for material paginator
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
        $isStatic = config("app.materials.types.{$validated['type']}.static");

        if ($isStatic) {
            $data['material'] = $materials->first();
            $ok = !empty($data['material']);
            $view = 'components.layouts.admin.static-material-list-item';
        } else {
            $data['materials'] = $materials;
            $ok = $materials->isNotEmpty();
            $view = 'components.layouts.admin.material-list';
        }

        return response()->json([
            'ok' => $ok,
            'html' => view($view, $data)->render(),
        ]);
    }
}
