<?php

namespace App\Traits\Models\Material;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasStatistic
{
    public const STATISTIC_FILLABLE = ['statistic'];

    public static function bootHasStatistic()
    {
        static::creating(function ($model) {
            // Set material statistic by default
            if (empty($model->statistic)) {
                $model->statistic = json_encode(
                    config('app.materials.statistic', [])
                );
            }
        });
    }

    protected function statistic(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value ?? '', true),
        );
    }
}
