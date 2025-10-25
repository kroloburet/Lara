<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

trait HasAlias
{
    public const ALIAS_FILLABLE = ['alias'];

    public static function bootHasAlias()
    {
        static::creating(function ($model) {
            // Set material alias
            if (empty(request('alias')) && empty($model->alias)) {
                $model->alias = Str::uuid();
            }
        });
    }

    protected function alias(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
        );
    }
}
