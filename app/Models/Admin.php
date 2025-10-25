<?php

namespace App\Models;

use App\Models\Abstract\Consumer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Consumer
{
    use HasFactory;

    /**
     * The fields of this model contain the fields of the parent
     * class and are supplemented by the traits fields.
     * Add additional fields to the merger if necessary.
     * @return array|string[]
     */
    public function getFillable(): array
    {
        return array_unique(array_merge(
            parent::getFillable(),

            // Marge Traits fillable
            static::LOG_FILLABLE,

        // Additional fields
        ));
    }

    /**
     * Run parent boot, traits boots, this model boot if necessary
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        // Traits boot registration

        // This model boot
        static::creating(function ($model) {
            // This model creates only moderators. The admin must be created once with a seeder.
            // Only moderator if type not specified (not seeder)
            if (request('type') || !$model->type) {
                $model->type = 'moderator';
            }
        });

    }
}
