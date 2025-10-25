<?php

/*
 * This is the main System configuration file.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Lara'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Error Log for Admin
    |--------------------------------------------------------------------------
    |
    | What errors should be caught on the server and report on
    | the details of the administrator
    |
    */

    'bug_report_status_codes' => [
        500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Application Available Locales
    |--------------------------------------------------------------------------
    |
    | List of languages used in the application. If you add another language files,
    | add this language tag to this list. Example: ['UA' => 'uk', 'EN' => 'en']
    |
    */

    'available_locales' => [
        'EN' => 'en', // <- First item mast by a fallback app locale
        'UA' => 'uk',
    ],

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),
    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Settings (admin|moderator manage)
    |--------------------------------------------------------------------------
    |
    | This is a configuration for the entire application that is managed
    | by the administrator and/or moderator. Settings are stored in the Settings table.
    |
    */

    'settings' => [
        'installedAt' => now()->timestamp,
        'access' => ['mode' => 'allowed'],
        'sitemap' => ['refresh' => 'auto'],
        'layout' => [ // Base layout settings
            'desktop' => [ // Base layout desktop control classnames
                'right-aside',
                'left-aside',
                'top-aside',
                'bottom-aside',
                'not-aside',
            ],
            'mobile' => [ // Base layout control classnames for mobile (> 800px)
                'top-aside-adaptive',
                'bottom-aside-adaptive',
                'not-aside-adaptive',
            ],
            'default' => [ // Model->type => layout settings by default
                'home' => [
                    'classes' => ['right-aside', 'bottom-aside-adaptive'],
                    'header' => true,
                    'asideWidth' => 30,
                    'layoutMaxWidth' => 1300,
                ],
                'contact' => [
                    'classes' => ['right-aside', 'bottom-aside-adaptive'],
                    'header' => true,
                    'asideWidth' => 30,
                    'layoutMaxWidth' => 1300,
                ],
                'category' => [
                    'classes' => ['right-aside', 'bottom-aside-adaptive'],
                    'header' => true,
                    'asideWidth' => 30,
                    'layoutMaxWidth' => 1300,
                ],
                'page' => [
                    'classes' => ['right-aside', 'bottom-aside-adaptive'],
                    'header' => true,
                    'asideWidth' => 30,
                    'layoutMaxWidth' => 1300,
                ],
            ],
        ],
        'paginatorLimit' => 10, // Get so much results for request
        'localeCookieName' => 'appLocale', // This is cookie name that stores language
        'statisticCookieName' => 'appStatistic', // This is cookie name that stores statistic
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Consumers
    |--------------------------------------------------------------------------
    |
    | Here you may specify the consumers configuration for your application.
    | Consumers must be Authenticatable!
    | All thet consumers extends Consumer abstract model.
    |
    */

//     Snippet of change the consumer settings
//            transaction(function () {
//                \App\Models\Admin::query()->where('type', 'moderator')->each(function ($consumer) {
//                    $settings = $consumer->settings;
//                    $newSettings = array_merge([
//                        'hidden' => false,
//                        ], $settings);
//                    dd($newSettings);
//                    $consumer->update(['settings' => json_encode($newSettings)]);
//                });
//            });

    'consumers' => [
        'types' => [
            'admin' => [ // Admin model type=admin
                'urlSegment' => 'admin', // URL segment after locale (2)
                'sitemap' => false, // If true, it will be present in the sitemap
                'tableName' => 'admins',
                'model' => App\Models\Admin::class,
                'permits' => ['moderator' => 'rcud', 'menu' => 'rcud', 'material' => 'rcud'], // scope => abilities (by default)
                'settings' => ['timezone' => 'UTC'], // Consumer settings (by default)
                'activityExpiry' => 3, // After so many minutes after the last activity will become offline
            ],
            'moderator' => [ // Admin model type=moderator
                'urlSegment' => 'moderator', // URL segment after locale (2)
                'sitemap' => false, // If true, it will be present in the sitemap
                'tableName' => 'admins',
                'model' => App\Models\Admin::class,
                'permits' => ['moderator' => 'r', 'menu' => 'r', 'material' => 'r'], // scope => abilities (by default)
                'settings' => ['timezone' => 'UTC'], // Consumer settings (by default)
                'activityExpiry' => 3, // After so many minutes after the last activity will become offline
            ],
//            'user' => [
//                'urlSegment' => 'user',
//                'sitemap' => true,
//                'tableName' => 'users',
//                'model' => App\Models\User::class,
//                'settings' => ['timezone' => 'UTC'],
//                'activityExpiry' => 3,
//            ],
        ],
        'permissions' => [
            'abilities' => [ // Base abilities
                'r', // Read ability
                'c', // Create ability
                'u', // Update ability
                'd', // Delete ability
            ],
            'scopes' => [ // Base scopes
                'moderator', // Admin model type=moderator
                'menu', // Menu model
                'material', // Models: Category, Page, Home etc. see app.materials.types
//                'user', // User model type=user
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Materials
    |--------------------------------------------------------------------------
    |
    | Material is the entity in the application that corresponds to the model of type.
    | This list should contain all allowed types of materials that are in the application
    | and other config data. All thet materials extends Material abstract model.
    |
    */

    'materials' => [
        'types' => [
            'category' => [ // Category model type=category
                'urlSegment' => 'category', // URL segment after locale (2)
                'static' => false, // If true, this type is singleton and only editable
                'sitemap' => true, // If true, it will be present in the sitemap
                'tableName' => 'categories',
                'model' => App\Models\Category::class,
                'media' => ['limit' => 25], // Config of media files
                'commentable' => false,
            ],
            'page' => [ // Page model type=page
                'urlSegment' => 'page', // URL segment after locale (2)
                'static' => false, // If true, this type is singleton and only editable
                'sitemap' => true, // If true, it will be present in the sitemap
                'tableName' => 'pages',
                'model' => App\Models\Page::class,
                'media' => ['limit' => 25], // Config of media files
                'commentable' => false,
            ],
            'home' => [ // HomePage model type=home
                'urlSegment' => '', // URL segment after locale (2)
                'static' => true, // If true, this type is singleton and only editable
                'sitemap' => true, // If true, it will be present in the sitemap
                'tableName' => 'home',
                'model' => App\Models\Home::class,
                'media' => ['limit' => 25], // Config of media files
                'commentable' => false,
            ],
            'contact' => [ // ContactPage model type=contact
                'urlSegment' => 'contact', // URL segment after locale (2)
                'static' => true, // If true, this type is singleton and only editable
                'sitemap' => true, // If true, it will be present in the sitemap
                'tableName' => 'contact',
                'model' => App\Models\Contact::class,
                'media' => ['limit' => 25], // Config of media files
                'commentable' => false,
            ],
        ],
        'statistic' => [ // Materials statistic by default
            'likes' => 0,
            'views' => 0,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | A validation rules of fields
    |
    */

    'validation_rules' => [
        'regex' => [
            'email' => '/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
            'phone' => '/^\+\d{1,3}(-\d{1,4})? \d+$/',
            'alias' => '/^[a-z0-9]+(-[a-z0-9]+)*$/',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Get content countries.json
    |--------------------------------------------------------------------------
    |
    | The file countries.json contains information about countries
    |
    */

    'get_countries_json' => file_get_contents(resource_path('data/countries.json')),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
