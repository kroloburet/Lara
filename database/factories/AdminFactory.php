<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Super Administrator Created
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => 'admin',
            'email' => 'kroloburet@gmail.com',
            'password' => Hash::make('aaAA&&55'),
            'permissions' => json_encode(config('app.consumers.types.admin.permits', [])),
            'settings' => json_encode(config('app.consumers.types.admin.settings', [])),
        ];
    }
}
