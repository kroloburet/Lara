<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Actions\PaginateFilteredModeratorsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\PaginateFilteredModeratorsRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class PaginateFilteredModeratorsListController extends Controller
{
    /**
     * Get HTML data for moderators paginator
     *
     * @param PaginateFilteredModeratorsRequest $request
     * @param PaginateFilteredModeratorsAction $action
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function __invoke(
        PaginateFilteredModeratorsRequest $request,
        PaginateFilteredModeratorsAction $action
    ): JsonResponse
    {
        $this->authorize('permits', ['moderator', 'r']);

        $validated = $request->validated();
        $moderators = $action->handle($validated);

        return response()->json([
            'html' => view(
                'components.layouts.admin.moderators-list',
                compact('moderators')
            )->render()
        ]);
    }
}
