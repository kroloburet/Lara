<?php

namespace App\Http\Controllers\XHR\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Front\StatisticRequest;
use Illuminate\Http\JsonResponse;

class ToggleStatisticKeyController extends Controller
{
    /**
     * Handle toggle record on statistic key in model
     *
     * @param StatisticRequest $request
     * @return JsonResponse
     */
    public function __invoke(
        StatisticRequest $request
    ): JsonResponse
    {
        $validated = $request->validated();
        $model = $request->model;

        $status = statistic($model)->toggleKey($validated['key']);
        $trigger = __("component.toggle_statistic_key.{$validated['key']}.$status");

        return response()->json(compact('status', 'trigger'));
    }
}
