<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public $timestamps = false;

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value ?? '', true),
        );
    }

    /**
     * Get value by key or return full settings collection
     *
     * @param string $key
     * @return Collection|null
     */
    public function get(?string $key = null): Collection|null
    {

        if (empty($key)) return $this->pluck('value', 'key')
            ->map(fn($val) => $val);

        $setting = $this->firstWhere('key', $key);

        if ($setting) return $setting->value;

        return null;
    }
}
