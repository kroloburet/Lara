<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Admin\ToggleBlockOrDeleteModeratorRequest;
use App\Models\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class DeleteModeratorController extends Controller
{
    /**
     * Handle of moderator delete
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
        $name = $moderator->name;

        abort_if(
            empty($moderator),
            403,
            __('auth.not_exist')
        );

        $moderator->forceDelete();

        $responseData['message'] = __('admin.moderator.list.del_done');
        $responseData['ok'] = true;

        // Push log event
        $currentConsumer = auth()->user();
        $currentConsumer->pushLogEvent("Consumer [moderator:{$name}] deleted");

        return response()->json($responseData);
    }
}
