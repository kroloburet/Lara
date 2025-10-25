<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class StatisticService
{
    protected array $defaultStatistic = [];
    protected string $guestStatisticsCookieName = 'appStatistic';
    protected Model|null $model;

    /**
     * Service for handling guest statistics,
     * toggling, and incrementing model statistics.
     *
     * @param Model|null $model Model with statistic
     */
    public function __construct(Model|null $model)
    {
        $this->model = $model;
        $this->guestStatisticsCookieName = config('app.settings.statisticCookieName');
        $this->defaultStatistic = config("app.materials.statistic", []);
    }

    protected function getModelKey(): string {
        $modelType = $this->model->type;
        $modelId = $this->model->id;
        return "$modelType:$modelId";
    }

    /**
     * Retrieve guest statistics from cookie as a Laravel Collection.
     *
     * @return Collection
     */
    public function getGuestStatistics(): Collection
    {
        return collect(
            json_decode(Cookie::get($this->guestStatisticsCookieName, '{}'), true)
        );
    }

    public function hasKeyInGuestStatistic(string $statisticKey): bool
    {
        $allStatistics = $this->getGuestStatistics();

        if ($allStatistics->isNotEmpty() && $statisticKey && is_string($statisticKey)) {
            $modelKey = $this->getModelKey();
            return $allStatistics->has($modelKey) && collect($allStatistics[$modelKey])->contains($statisticKey);
        }

        return false;
    }

    /**
     * Save guest statistics to cookie.
     *
     * @param Collection $guestStatistics
     * @param int $minutes Cookie lifetime in minutes (default: 1 year)
     * @return \Illuminate\Support\Facades\Cookie
     */
    protected function saveGuestStatistics(Collection $guestStatistics, int $minutes = 60 * 24 * 365)
    {
        // Limit to 100 entries to prevent cookie overflow
        if ($guestStatistics->count() > 100) {
            $guestStatistics = $guestStatistics->slice(-100);
        }

        return cookie()->queue(
            $this->guestStatisticsCookieName,
            $guestStatistics->toJson(),
            $minutes // 1 year
        );
    }

    /**
     * Toggle a statistic key (e.g., likes, views) for a model.
     *
     * @param string $statisticKey
     * @return string Status string (added|removed|error)
     */
    public function toggleKey(string $statisticKey): string
    {
        if (empty($this->model)) {
            return 'error';
        }

        $statistic = $this->model->statistic ?? $this->defaultStatistic;
        $guestStatistics = $this->getGuestStatistics();
        $modelKey = $this->getModelKey();

        // Initialize empty array for the model if not exists
        if (!$guestStatistics->has($modelKey)) {
            $guestStatistics->put($modelKey, []);
        }

        // Toggle the statistic key
        if (collect($guestStatistics[$modelKey])->contains($statisticKey)) {
            // Guest already performed the action - decrement and remove
            $statistic[$statisticKey] = max(0, $statistic[$statisticKey] - 1);
            $guestStatistics->put($modelKey, array_diff($guestStatistics[$modelKey], [$statisticKey]));
            $status = 'removed';
        } else {
            // Guest hasn't performed the action - increment and add
            $statistic[$statisticKey]++;
            $guestStatistics->put($modelKey, array_merge($guestStatistics[$modelKey], [$statisticKey]));
            $status = 'added';
        }

        // Update model statistic
        $this->model->statistic = json_encode($statistic);
        $this->model->save();

        // Save Guest Statistics and return status string
        $this->saveGuestStatistics($guestStatistics);
        return $status;
    }

    /**
     * Increment a statistic key if the guest hasn't performed the action.
     *
     * @param string $statisticKey
     * @return array
     */
    public function incrementKey(string $statisticKey): array
    {
        if (empty($this->model)) {
            return [];
        }

        $statistic = $this->model->statistic ?? $this->defaultStatistic;
        $guestStatistics = $this->getGuestStatistics();
        $modelKey = $this->getModelKey();

        // Initialize empty array for the model if not exists
        if (!$guestStatistics->has($modelKey)) {
            $guestStatistics->put($modelKey, []);
        }

        // Increment the key if not already performed
        if (!collect($guestStatistics[$modelKey])->contains($statisticKey)) {
            $statistic[$statisticKey]++;
            $guestStatistics->put($modelKey, array_merge($guestStatistics[$modelKey], [$statisticKey]));

            // Update model statistic
            $this->model->statistic = json_encode($statistic);
            $this->model->save();
        }

        // Return updated statistic and cookie
        return [
            'statistic' => $statistic,
            'cookie' => $this->saveGuestStatistics($guestStatistics),
        ];
    }
}
