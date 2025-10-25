<?php

namespace App\Models;

use App\Models\Abstract\Material;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Material
{
    use HasFactory;

    protected $table = 'contact';

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
            [
                'location',
                'links',
                'emails',
                'phones',
                'social_networks',
            ]
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

    /**
     * Location data
     *
     * @return Attribute
     */
    protected function location(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value ?? '', true),
        );
    }

    /**
     * Links data
     *
     * @return Attribute
     */
    protected function links(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value ?? '', true),
        );
    }

    /**
     * Emails data
     *
     * @return Attribute
     */
    protected function emails(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value ?? '', true),
        );
    }

    /**
     * Phones data
     *
     * @return Attribute
     */
    protected function phones(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value ?? '', true),
        );
    }

    /**
     * Phones data
     *
     * @return Attribute
     */
    protected function socialNetworks(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value ?? '', true),
        );
    }
}
