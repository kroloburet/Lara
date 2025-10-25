<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\Home;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Home>
 */
class HomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => 'home',
            'storage' => Str::ulid(),
            'layout' => json_encode(config('app.settings.layout.default.home')),
        ];
    }

    /**
     * Configure the factory to create related Home records for each locale.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Home $home) {
            $locales = config('app.available_locales');

            foreach ($locales as $locale) {
                Content::factory()
                    ->forLocale($locale)
                    ->create([
                        'material_id' => $home->id,
                        'material_type' => $home->type,
                    ]);
            }
        });
    }
}
