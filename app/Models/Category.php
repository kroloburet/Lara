<?php

namespace App\Models;

use App\Models\Abstract\Material;
use App\Traits\{Models\Blockable, Models\HasAlias, Models\Material\Categorizable, Models\Material\HasStatistic};

class Category extends Material
{
    use HasAlias, Categorizable, HasStatistic, Blockable;

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
            static::ALIAS_FILLABLE,
            static::CATEGORY_FILLABLE,
            static::STATISTIC_FILLABLE,

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
        static::bootHasAlias();
        static::bootHasStatistic();

        // This model boot
    }
}
