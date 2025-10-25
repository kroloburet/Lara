<?php

namespace Database\Seeders;

use App\Models\Admin;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Contact;
use App\Models\Home;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Administrator
        Admin::factory(1)->create();

        // Create Contact Page With Content
        Contact::factory(1)->create();

        // Create Contact Page With Content
        Home::factory(1)->create();
    }
}
