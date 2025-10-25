<?php

use App\Models\Abstract\Consumer;
use App\Models\Abstract\Material;
use App\Models\Menu;
use App\Services\AppSettingsService;
use App\Services\ConsumerSettingsService;
use App\Services\MaterialMediaService;
use App\Services\StatisticService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

##########################################
## Settings
##########################################

if (! function_exists('appSettings')) {

    /**
     * AppSettingsService singleton resolver
     *
     * This manages the app settings.
     * Called without a parameter will return an instance of the AppSettingsService class.
     * Pass the parameter in dot notation to get the desired settings or null.
     *
     * @param string|null $dotTargetKey
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @see /Services/AppSettingsService.php
     */
    function appSettings(string|null $dotTargetKey = null): mixed
    {
        app()->singletonIf('appSettings', function () {
            return new AppSettingsService();
        });

        if (empty($dotTargetKey)) return app('appSettings');

        return app('appSettings')->get($dotTargetKey);
    }
}

##########################################
## Consumer
##########################################

if (! function_exists('consumerBuilder')) {

    /**
     * Return Consumer|Builder of consumer type
     *
     * @param string $consumerType Consumer type (config app.consumers.types)
     * @return Consumer|Builder
     */
    function consumerBuilder(string $consumerType): Consumer|Builder
    {
        $consumerConfigData = config("app.consumers.types.{$consumerType}");
        if (! $consumerConfigData || ! isset($consumerConfigData['model'])) {
            throw new \InvalidArgumentException("Invalid consumer type: {$consumerType}", 500);
        }

        $model = resolve($consumerConfigData['model']);
        $query = $model->newQuery();

        // Admin to give everything
        if (isAdminCheck() && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            return $query->withTrashed();
        }

        return $query;
    }
}

if (! function_exists('consumerSettings')) {

    /**
     * ConsumerSettingsService resolver
     *
     * This manages the consumer settings of the consumer.
     *
     * - Pass the Consumer model (Admin|User) as the first parameter,
     * to work with consumer settings of this particular model.
     * - Pass a string ('admin|user') as the first parameter to work
     * with consumer settings of the authenticated consumer.
     *
     * Called without a second parameter, it returns an instance
     * of the ConsumerSettingsService class. Pass the second parameter
     * in dot notation to get the desired value or null.
     *
     * @param Consumer|string $consumer
     * @param string|null $dotTargetKey
     * @param mixed|null $default
     * @return mixed
     * @see /Services/ConsumerSettingsService.php
     */
    function consumerSettings(
        Consumer|string $consumer,
        string|null  $dotTargetKey = null,
        mixed        $default = null,
    ): mixed
    {
        $service = new ConsumerSettingsService($consumer);

        if (empty($dotTargetKey)) return $service;

        return $service->get($dotTargetKey, $default);
    }
}

if (! function_exists('consumerDateFormat')) {

    /**
     * Get the date in a consumer format
     *
     * - Pass in the second parameter the model to get
     * consumer format (Admin|User) or default format.
     * - Pass in the second parameter a string
     * ('admin|user' see app.consumers.types),
     * to get the consumer format of the authenticated consumer
     * or the default format.
     * - Pass or leave null by default in the second parameter,
     * to get the consumer format of the first authenticated
     * of the consumer (Admin|User) or the default format.
     *
     * @param Carbon|int|null $date
     * @param Consumer|string|null $consumer
     * @param string $format
     * @param bool $showTimezone
     * @return string|null
     */
    function consumerDateFormat(
        Carbon|int|null             $date,
        Consumer|string|null $consumer = null,
        string                      $format = 'Y-m-d',
        bool                        $showTimezone = false
    ): ?string
    {
        // No date provided
        if (is_null($date)) {
            return null;
        }

        // Convert timestamp to Carbon instance
        if (is_int($date)) {
            $date = Carbon::createFromTimestamp($date);
        }

        // Determine timezone
        $timezone = config('app.timezone'); // Default timezone

        if (!$consumer) {
            // Find the first authenticated user from consumer types
            $authGuard = collect(config('app.consumers.types', []))
                ->keys() // Get only the keys (e.g., 'admin', 'user')
                ->first(function ($type) {
                    return auth($type)->check(); // Check if user is authenticated for this guard
                });

            // Get user if authenticated guard is found
            $consumer = $authGuard ? auth($authGuard)->user() : null;

            // Get timezone from consumer settings if found
            if ($consumer) {
                $timezone = consumerSettings($consumer, 'timezone') ?? $timezone;
            }
        } else {
            // If consumer is a string, assume it's a guard type and get the user
            if (is_string($consumer)) {
                $consumer = auth($consumer)->user();
            }
            // Get timezone from consumer settings
            $timezone = consumerSettings($consumer, 'timezone') ?? $timezone;
        }

        // Format the date with the determined timezone
        $formattedDate = $date->setTimezone($timezone)->translatedFormat($format);

        // Return formatted date with or without timezone
        return $showTimezone ? "$formattedDate <time>$timezone</time>" : $formattedDate;
    }
}

if (! function_exists('consumerDateTimeFormat')) {

    /**
     * Get the date and time (Y-m-d H:i) in a user timezone
     *
     * - Pass in the second parameter the model to get
     * consumer user format (Admin|User) or default format.
     * - Pass in the second parameter a string ('admin|user'),
     * to get the consumer format of the authenticated user
     * or the default format.
     * - Pass or leave null by default in the second parameter,
     * to get the consumer format of the first authenticated
     * of the user (Admin|User) or the default format.
     *
     * @param Carbon|int|null $date
     * @param Consumer|string|null $consumer
     * @param bool $showTimezone
     * @return string|null
     */
    function consumerDateTimeFormat(
        Carbon|int|null   $date,
        Consumer|string|null $consumer = null,
        bool              $showTimezone = false
    ): string|null
    {
        return consumerDateFormat($date, $consumer, 'Y-m-d H:i', $showTimezone);
    }
}

if (! function_exists('customRedirectUrl')) {

    /**
     * Get or set the redirect URL from session.
     *
     * Saves the URL value that can be obtained
     * then and used as redirecting
     *
     * @param string $action "get" or "set"
     * @param string $url Save URL if "set" or default URL if "get"
     * @return string|null
     */
    function customRedirectUrl(string $action, string $url = '/'): ?string
    {
        $allowedActions = ['get', 'set'];
        $key = 'custom_redirect_url';

        if (! in_array($action, $allowedActions)) {
            throw new \InvalidArgumentException("Argument '$action' is not allowed!");
        }

        if ($action === 'set') {
            // Save URL to Session
            session()->put($key, $url);
            return $url;
        }

        // Get from session and forget
        $targetUrl = session()->get($key, $url);
        session()->forget($key);

        // Return URL or default
        return $targetUrl;
    }
}

##########################################
## Material
##########################################

if (! function_exists('materialBuilder')) {

    /**
     * Return Material|Builder of material type
     *
     * @param string $materialType Material type (config app.materials.types)
     * @return Material|Builder
     */
    function materialBuilder(string $materialType): Material|Builder
    {
        $materialConfigData = config("app.materials.types.{$materialType}");
        if (! $materialConfigData || ! isset($materialConfigData['model'])) {
            throw new \InvalidArgumentException("Invalid material type: {$materialType}", 500);
        }

        $model = resolve($materialConfigData['model']);
        $query = $model->newQuery();

        // Admin to give everything
        if (isAdminCheck() && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            return $query->withTrashed();
        }

        return $query;
    }
}

if (! function_exists('routeToMaterial')) {

    /**
     * Get URL to material
     *
     * @param Material|null $material Material
     * @param bool $absolute If true, relative URL returned
     * @return string
     */
    function routeToMaterial(
        Material|null $material,
        bool $absolute = false
    ): string
    {
        try {
            if (! $material) return '';

            $type = $material->type;
            $isStatic = config("app.materials.types.{$type}.static");

            // Static materials may not have this field
            if ($isStatic) {
                $segment = config("app.materials.types.{$type}.urlSegment", '');
                return $absolute ? url($segment) : "/{$segment}";
            }

            return route($material->type, ['alias' => $material->alias], $absolute);
        } catch (Exception) {
            return '';
        }
    }
}

if (! function_exists('materialLayoutSettings')) {

    /**
     * Get material layout settings or
     * default layout settings of given materialType
     *
     * @param string $materialType Type of material model (Model->type)
     * @param Model|null $material Material model fo retrieve it settings
     * @return array Array of settings or empty array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    function materialLayoutSettings(string $materialType, Model $material = null): array
    {
        $materialType = $material?->type ?? $materialType;
        $layoutSettings = ! empty($material->layout) && is_array($material->layout)
            ? $material->layout
            : appSettings("layout.default.{$materialType}");

        return $layoutSettings ?? [];
    }
}

if (! function_exists('materialBgImageUrl')) {

    /**
     * Get URL to material bg image file or URL to default bg image file.
     * The background images are on the url /uploads/materials/storage_id/bgImage/file
     *
     * @param Material|null $material Material
     * @param bool $nullable Return null if the file does not exist
     * @return string|null
     */
    function materialBgImageUrl(
        Material|null $material = null,
        bool $nullable = false
    ): string|null
    {
        $default = !$nullable ? url('images/bg_image_default.png') : null;

        if (! $material) {
            return $default;
        }

        $storage = $material->storage;
        $files = Storage::disk('materials')->files("$storage/bgImage");

        if (! empty($files)) {
            return Storage::disk('materials')->url($files[0]);
        }

        return $default;
    }
}

if (! function_exists('materialMedia')) {

    /**
     * Retrieves an instance of the MaterialMediaService for a given material and path.
     *
     * @param Material|null $material Material model.
     * @param string $path The path to the directory of Material storage media folder in dot notation ("public.slider" => "materials/storage_id/media/public/slider").
     * @return MaterialMediaService
     */
    function materialMedia(
        Material|null $material,
        string $path
    ): MaterialMediaService
    {
        return new MaterialMediaService($material, $path);
    }
}

##########################################
## Menu
##########################################

if (! function_exists('getMenu')) {

    /**
     * Get Menu tree Collection
     *
     * If the locale is not transmitted, the current
     * locale of the application will be obtained
     *
     * @param string|null $locale Locale of language version
     * @param bool $withHidden If true, get along with the hidden items
     * @return Collection
     */
    function getMenu(string $locale = null, bool $withHidden = false): Collection
    {
        $locale = $locale && in_array($locale, config('app.available_locales', []))
            ? $locale
            : app()->getLocale();
        $builder = $withHidden
            ? Menu::withTrashed()->where('locale', $locale)
            : Menu::query()->where('locale', $locale);

        return $builder
            ->whereNull('parent_id')
            ->with('allChildren')
            ->orderBy('order')
            ->get();
    }
}

##########################################
## Check
##########################################

if (! function_exists('isAdminCheck')) {

    /**
     * Check administrator authentication by type or any type by default
     *
     * @param string|null $type null or "admin" or "moderator"
     * @return bool
     */
    function isAdminCheck(string $type = null): bool
    {
        $allowedTypes = ['admin', 'moderator'];
        $guard = auth('admin');

        if (! $type) {
            return $guard->check();
        }

        if (! in_array($type, $allowedTypes)) {
            return false;
        }

        return $type === $guard->user()?->type;
    }
}

if (! function_exists('markIfBlocked')) {

    /**
     * Return CSS class "mark-as-blocked"
     * if Material is blocked (soft delete) or empty string
     *
     * @param Material|null $model Material model
     * @return string "mark-as-blocked" or empty string
     */
    function markIfBlocked(
        Material|null $model
    ): string
    {
        if (
            $model &&
            in_array(SoftDeletes::class, class_uses_recursive($model)) &&
            $model->isBlocked()
        ) {
            return 'mark-as-blocked';
        }

        return '';
    }
}

if (! function_exists('isBot')) {

    /**
     * Checks if the request is from a bot
     *
     * @param Request $request
     * @return bool
     */
    function isBot(Request $request): bool
    {
        $bots = [
            'googlebot',
            'bingbot',
            'slurp', // Yahoo
            'duckduckbot',
            'baiduspider',
            'yandexbot',
            'facebot',
            'ia_archiver', // Alexa
        ];
        $userAgent = $request->header('User-Agent', '');

        foreach ($bots as $bot) {
            if (stripos($userAgent, $bot) !== false) return true;
        }

        return false;
    }
}

##########################################
## Other
##########################################

if (! function_exists('transaction')) {

    /**
     * Database transaction
     *
     * @param callable $callback
     * @param int $attempts
     * @return mixed
     */
    function transaction(callable $callback, int $attempts = 1): mixed
    {
        if (DB::transactionLevel() > 0) {
            return $callback();
        }

        return DB::transaction($callback, $attempts);
    }
}

if (! function_exists('stripTagsAndLimit')) {

    /**
     * Strip HTML, PHP tags, limit then and return a string
     *
     * @param string|null $html HTML string
     * @param int $limit Limit the number of characters
     * @param string $end End of limited string
     * @return string
     */
    function stripTagsAndLimit(
        string $html = null,
        int $limit = 100,
        string $end = '...'
    ): string|null
    {
        if (! $html) return '';

        return Str::limit(strip_tags($html), $limit, $end);
    }
}

if (! function_exists('statistic')) {

    /**
     * Resolve the StatisticService
     * - Service for handling guest statistics,
     * toggling, and incrementing model statistics.
     *
     * @param Model|null $model Model with statistic
     * @return StatisticService
     */
    function statistic(Model|null $model): StatisticService
    {
        return new StatisticService($model);
    }
}

if (! function_exists('getUrlPath')) {

    /**
     * Get URL path with query parameters if exists
     *
     * @param Request $request
     * @return string
     */
    function getUrlPath(Request $request): string
    {
        $path = $request->path();
        $query = $request->getQueryString();
        return $query ? "/{$path}?{$query}" : "/{$path}";
    }
}

if (! function_exists('getCountriesCollect')) {

    /**
     * Get collection from countries.json data
     *
     * @return \Illuminate\Support\Collection
     */
    function getCountriesCollect(): \Illuminate\Support\Collection
    {
        $json = config('app.get_countries_json');
        $arr = json_decode($json, true);
        return collect($arr);
    }
}
