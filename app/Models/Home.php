<?php

namespace App\Models;

use App\Models\Abstract\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Home extends Material
{
    use HasFactory;

    protected $table = 'home';

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
    }
}
