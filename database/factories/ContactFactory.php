<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => 'contact',
            'storage' => Str::ulid(),
            'layout' => json_encode(config('app.settings.layout.default.contact')),
        ];
    }

    /**
     * Configure the factory to create related Content records for each locale.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Contact $contact) {
            $locales = config('app.available_locales');

            foreach ($locales as $locale) {
                Content::factory()
                    ->forLocale($locale)
                    ->create([
                        'material_id' => $contact->id,
                        'material_type' => $contact->type,
                    ]);
            }
        });
    }
}
