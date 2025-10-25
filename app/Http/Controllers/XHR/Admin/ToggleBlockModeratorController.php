<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Admin\ToggleBlockOrDeleteModeratorRequest;
use App\Models\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class ToggleBlockModeratorController extends Controller
{
    /**
     * Handle toggle block of moderator
     *
     * @param ToggleBlockOrDeleteModeratorRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function __invoke(
        ToggleBlockOrDeleteModeratorRequest $request
    ): JsonResponse
    {
        $this->authorize('permits', ['moderator', 'd']);

        $id = $request->validated('id');
        $moderator = Admin::withTrashed()->firstWhere(['type' => 'moderator', 'id' => $id]);

        abort_if(
            empty($moderator),
            403,
            __('auth.not_exist')
        );

        $moderator->isBlocked()
            ? $moderator->unblock()
            : $moderator->block();

        // Push log event
        $stage = !$moderator->isBlocked() ? 'blocked' : 'unblocked';
        $currentConsumer = auth()->user();
        $currentConsumer->pushLogEvent("Consumer [moderator:{$moderator->name}] {$stage}");

        return response()->json([
            'ok' => true,
            'message' => $moderator->isBlocked()
                ? __('admin.moderator.list.block_done')
                : __('admin.moderator.list.unblock_done'),
        ]);
    }
}
