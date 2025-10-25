<?php

namespace App\Models\Abstract;

use App\Models\Content;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

/**
 * This is the basic abstract class of the material model.
 * The model expanding this class contains basic fields and methods of materials in the system.
 */
abstract class Material extends Model
{
    // These fields should have every material
    protected $fillable = [
        'type',
        'storage',
        'layout',
        'robots',
        'css',
        'js',
    ];

    /**
     * Boot method for Material base functionality.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            // Set storage ULID if not provided
            if (empty($model->storage)) {
                $model->storage = Str::ulid();
            }
        });
    }

    ##########################################
    ## Material Layout
    ##########################################

    /**
     * Layout attribute accessor/mutator.
     */
    protected function layout(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value ?? '', true),
        );
    }

    ##########################################
    ## Material Content
    ##########################################

    /**
     * Morph many contents relation.
     */
    public function contents(): MorphMany
    {
        return $this->morphMany(Content::class, 'material');
    }

    /**
     * Get fi
     */
    public function content(string $locale = null)
    {
        if (!empty($locale) && in_array($locale, config('app.available_locales'))) {
            return $this->contents()->firstWhere('locale', $locale);
        }

        return $this->contents[0];
    }

    ##########################################
    ## Other
    ##########################################
}
