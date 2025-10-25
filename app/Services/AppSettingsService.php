<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;

/**
 * This class manages the administrative settings
 * of the application, which are stored in the
 * database (Setting Model).
 */
class AppSettingsService
{
    private Setting $model;
    private Collection $hashmap;

    public function __construct()
    {
        $this->model = resolve(Setting::class);
        $this->hashmap = $this->model->get();
    }

    /**
     * Called without a parameter will return a Collection of all settings.
     * Pass the parameter in dot notation to get the desired setting or null.
     *
     * @param string|null $dotTargetKey
     * @return mixed
     */
    public function get(string|null $dotTargetKey = null): mixed
    {
        if (empty($dotTargetKey)) return $this->hashmap;
        return data_get($this->hashmap, $dotTargetKey);
    }

    /**
     * Set the setting value using dot notation
     *
     * @param string $dotTargetKey
     * @param mixed $value
     * @return bool
     */
    public function set(string $dotTargetKey, mixed $value)
    {
        if (! $this->has($dotTargetKey)) return false;

        $settings = $this->hashmap->all();
        data_set($settings, $dotTargetKey, $value);
        $this->hashmap = collect($settings);

        $keys = explode('.', $dotTargetKey);
        $indexKey = array_shift($keys);

        // Push log event
        $currentConsumer = auth()->user();
        $currentConsumer->pushLogEvent("System settings [$dotTargetKey] updated");

        return (bool) $this->model
            ->where('key', $indexKey)
            ->update(['value' => $settings[$indexKey]]);
    }

    /**
     * Determinate if exists the setting using dot notation
     *
     * @param string $dotTargetKey
     * @return bool
     */
    public function has(string $dotTargetKey)
    {
        return ! empty($this->get($dotTargetKey));
    }
}
