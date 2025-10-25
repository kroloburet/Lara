<?php

namespace App\Services;

use App\Models\Abstract\Consumer;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * This class get and manages the consumer settings
 * of the Authenticatable consumer. See app.consumers.types
 */
class ConsumerSettingsService
{
    private Consumer|null $consumer;
    private Collection $settings;

    /**
     * Instance of ConsumerSettingsService
     *
     * * Pass the Consumer model (Admin|User) as the first parameter,
     * to work with consumer settings of this particular model.
     * * Pass a string ('admin|user' see app.consumers.types)
     * as the first parameter to work with consumer settings
     * of the authenticated consumer.
     *
     * @param Consumer|string $consumer
     */
    public function __construct(Consumer|string $consumer)
    {
        if (is_string($consumer)) {
            if (
                ! $consumer ||
                ! key_exists($consumer, config('app.consumers.types', []))
            ) {
                throw new \RuntimeException(
                    '[ConsumerSettingsService] The transferred consumer type cannot have settings!'
                );
            }

            $this->consumer = auth($consumer)->user();
        } else {
            $this->consumer = $consumer;
        }

        $this->settings = collect(
            $this->consumer
                ? $this->consumer->settings
                : config("app.consumers.types.{$consumer}.settings")
        );
    }

    /**
     * Called without a parameter will return a Collection of all consumer settings.
     * Pass the parameter in dot notation to get the desired settings or null.
     *
     * @param string|null $dotTargetKey
     * @param mixed $default
     * @return mixed
     */
    public function get(string|null $dotTargetKey = null, mixed $default = null): mixed
    {
        if (empty($dotTargetKey)) return $this->settings;
        return data_get($this->settings, $dotTargetKey, $default);
    }

    /**
     * Set the setting of the authenticated consumer.
     *
     * @param string $dotTargetKey The dot-notation key for the setting.
     * @param mixed $value The value to set (can be string, array, boolean, etc., including JSON string).
     * @return bool True if the setting was updated successfully, false otherwise.
     */
    public function set(string $dotTargetKey, mixed $value)
    {
        // Authorization check and ensure the target key exists.
        if (!$this->has($dotTargetKey) || !$this->consumer) return false;

        return transaction(function () use ($dotTargetKey, $value) {
            // Attempt to decode JSON string into a PHP array/object if valid.
            if (is_string($value) && $this->isJson($value)) {
                $value = json_decode($value, true);
            }

            // Get current settings and convert to array if it's a Collection.
            $settings = $this->settings->toArray();

            // Set the new value using dot notation.
            data_set($settings, $dotTargetKey, $value);
            $this->settings = collect($settings); // Update the internal Collection instance
            $result = $this->consumer->update(['settings' => $settings]);

            // Push log event
            $this->consumer->pushLogEvent("Consumer settings [$dotTargetKey] updated");

            return $result;
        });
    }

    /**
     * Determinate if exists the setting path using dot notation
     *
     * @param string $dotTargetKey
     * @return bool
     */
    public function has(string $dotTargetKey): bool
    {
        if (! $dotTargetKey) return false;

        return Arr::has($this->settings->all(), $dotTargetKey);
    }

    /**
     * Checks if a given string is a valid JSON.
     * This method can be moved to a separate helper or trait if needed elsewhere.
     *
     * @param string $string
     * @return bool
     */
    private function isJson(string $string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
