<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'material_id' => 1, // Will be set in the seeder
            'material_type' => 'home', // Will be set in the seeder
            'locale' => 'en', // Default locale, will be overridden
            'title' => 'Hello, Lara!', // Will be overridden
            'description' => 'Minimal CMS based on Laravel', // Will be overridden
            'content' => 'Minimal CMS based on Laravel', // Will be overridden
            // Add other fields as needed for your content table
        ];
    }

    /**
     * Configure the locale for the content.
     *
     * @param string $locale
     * @return $this
     */
    public function forLocale(string $locale)
    {
        return $this->state([
            'locale' => $locale,
            'title' => __("base.default_title", [], $locale),
            'description' => __("base.default_meta_desc", [], $locale),
            'content' => __("base.default_desc", [], $locale),
        ]);
    }
}
