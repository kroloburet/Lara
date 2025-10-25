<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Admin\UpdateSecurityRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UpdateSecurityController extends Controller
{
    /**
     * Handle of admin security store
     *
     * @param UpdateSecurityRequest $request
     * @return JsonResponse
     */
    public function __invoke(
        UpdateSecurityRequest $request
    ): JsonResponse
    {
        $validated = $request->validated();
        $admin = $request->admin;

        // Update admin data
        if (empty($validated['password'])) {
            Arr::forget($validated, 'password');
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        $admin->update($validated);

        // Push log event
        $currentConsumer = auth()->user();
        $currentConsumer->pushLogEvent("Consumer [{$admin->type}:{$admin->name}] security updated");

        // Response
        $responseData = ['message' => __('base.Changes_saved')];
        return response()->json($responseData);
    }
}
