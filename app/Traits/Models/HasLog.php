<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Use this trait in models where there is a BD 'log' field.
 * So you can record and view the history (log) of events that have come from the model.
 */
trait HasLog
{
    public const LOG_FILLABLE = ['log'];

    /**
     * Get the history (log) of the events of this model.
     *
     * @return Attribute
     */
    protected function log(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value ?? '', true) ?? [],
        );
    }

    /**
     * Write a description of the event in the model log column (the last top)
     *
     * @param string $event
     * @param float|int|string|null $timestamp
     * @return bool
     */
    public function pushLogEvent(string $event, float|int|string|null $timestamp = null): bool
    {
        $newLogEntry = [
            'timestamp' => $timestamp ?? now()->timestamp,
            'event' => $event,
        ];

        $log = $this->log;
        array_unshift($log, $newLogEntry);

        return $this->update(['log' => json_encode($log)]);
    }
}
