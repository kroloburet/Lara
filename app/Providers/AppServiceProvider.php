<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Content;
use App\Models\Home;
use App\Models\Page;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // The map of aliases
        Relation::enforceMorphMap([
            'category' => Category::class,
            'page' => Page::class,
            'home' => Home::class,
            'contact' => Contact::class,
            'content' => Content::class,
            'admin' => Admin::class,
//            'user' => User::class,
        ]);

        // Global validation rule for password
        Password::defaults(function () {
            return Password::min(8)->mixedCase()->numbers()->symbols();
        });
    }
}
