<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Admin\UpdateModeratorRequest;
use App\Models\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UpdateModeratorController extends Controller
{
    /**
     * Handle of moderator updating
     *
     * @param UpdateModeratorRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function __invoke(
        UpdateModeratorRequest $request
    ): JsonResponse
    {
        $this->authorize('permits', ['moderator', 'u']);

        $validated = $request->validated();
        $moderator = Admin::withTrashed()->firstWhere(['type' => 'moderator', 'id' => $validated['id']]);

        abort_if(
            empty($moderator),
            403,
            __('auth.not_exist')
        );

        // Update moderator data
        if (empty($validated['password'])) {
            Arr::forget($validated, 'password');
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        $moderator->update($validated);

        // Push log event
        $currentConsumer = auth()->user();
        $currentConsumer->pushLogEvent("Consumer [moderator:{$moderator->name}] updated");

        // Response
        $responseData = ['message' => __('base.Changes_saved')];
        return response()->json($responseData);
    }
}
