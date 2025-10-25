<?php

namespace App\Http\Controllers\XHR;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\GetConsumersActivityRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Throwable;

class GetConsumersActivityDataController extends Controller
{
    /**
     * Fetch real-time activity data for multiple consumer types.
     * Groups consumers by type for efficient database queries.
     * Returns activity status with unique "type::id" keys.
     *
     * @param GetConsumersActivityRequest $request
     * @return JsonResponse
     */
    public function __invoke(GetConsumersActivityRequest $request): JsonResponse
    {
        try {
            $validConsumers = $request->validated()['consumers'];
            $activityData = $this->fetchActivityDataByType($validConsumers);

            return response()->json($activityData);
        } catch (Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch activity data grouped by consumer type for efficient querying.
     * Uses caching to reduce database load for frequent requests.
     *
     * @param array $validConsumers
     * @return array
     */
    private function fetchActivityDataByType(array $validConsumers): array
    {
        $activityData = [];

        // Group consumers by type
        $consumersByType = collect($validConsumers)
            ->groupBy('type')
            ->map(fn ($group) => $group->pluck('id')->unique()->values()->toArray());

        foreach ($consumersByType as $type => $ids) {
            if (empty($ids)) {
                continue;
            }

            $typeActivityData = $this->getCachedActivityData($type, $ids);
            $activityData = array_merge($activityData, $this->formatActivityKeys($type, $typeActivityData));
        }

        return $activityData;
    }

    /**
     * Get activity data with caching for specific consumer type.
     * Cache key includes type and sorted IDs for consistency.
     *
     * @param string $type
     * @param array $ids
     * @return array
     */
    private function getCachedActivityData(string $type, array $ids): array
    {
        sort($ids); // Ensure consistent cache key
        $cacheKey = "consumers_activity:{$type}:" . md5(implode(',', $ids));

        return Cache::remember($cacheKey, now()->addSeconds(10), function () use ($type, $ids) {
            return consumerBuilder($type)
                ->select(['id', 'last_activity_at'])
                ->whereIn('id', $ids)
                ->get()
                ->keyBy('id')
                ->map(fn ($consumer) => $this->formatConsumerActivity($consumer))
                ->toArray();
        });
    }

    /**
     * Format consumer activity data for API response.
     *
     * @param mixed $consumer
     * @return array
     */
    private function formatConsumerActivity($consumer): array
    {
        return [
            'online' => $consumer->isActive(),
            'lastActivity' => consumerDateTimeFormat(
                    $consumer->lastActivity(),
                    null,
                    true
                ) ?? __('base.Was_not_activity'),
        ];
    }

    /**
     * Format activity data with "type::id" composite keys.
     *
     * @param string $type
     * @param array $activityData
     * @return array
     */
    private function formatActivityKeys(string $type, array $activityData): array
    {
        return collect($activityData)
            ->mapWithKeys(fn ($data, $id) => ["{$type}::{$id}" => $data])
            ->toArray();
    }
}
