<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Admin\CreateModeratorRequest;
use App\Models\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class CreateModeratorController extends Controller
{
    /**
     * Handle moderator creating
     *
     * @param CreateModeratorRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function __invoke(
        CreateModeratorRequest $request,
    ): JsonResponse
    {
        $this->authorize('permits', ['moderator', 'c']);

        $validated = $request->validated();
        $validated['type'] = 'moderator';

        $moderator = Admin::create($validated);

        // Push log event
        $currentConsumer = auth()->user();
        $currentConsumer->pushLogEvent("Consumer [moderator:{$moderator->name}] created");

        // Response
        return response()->json([
            'message' => __('admin.moderator.add.done'),
            'redirect' => route('admin.moderators'),
        ]);
    }
}
