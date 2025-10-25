<?php
namespace App\Services;

class LocalizationService
{
    /**
     * Get user locale
     *
     * @return string
     */
    public function getLocale(): string
    {
        try {
            $uriLocale = request()->segment(1);
            $cookieLocale = request()->cookie(config('app.settings.localeCookieName'));
            $availableLocales = config('app.available_locales', []);
            $fallbackLocale = config('app.fallback_locale', 'en');

            // 1. Check the locale in the URL
            if ($uriLocale && in_array($uriLocale, $availableLocales)) {
                return $uriLocale;
            }

            // 2. Check the locale in the cookies
            if ($cookieLocale && in_array($cookieLocale, $availableLocales)) {
                return $cookieLocale;
            }

            // 3. http_accept_language to determine the Browser locale
            $browserLocale = \Locale::lookup(
                $availableLocales,
                $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en',
                true,
                $fallbackLocale
            );

            return $browserLocale ?: $fallbackLocale;

        } catch (\Exception $e) {
            return config('app.fallback_locale', 'en');
        }
    }

    /**
     * Set and return user locale
     *
     * @return string
     */
    public function setLocale(): string
    {
        $locale = $this->getLocale();
        $cookieLocale = request()->cookie(config('app.settings.localeCookieName'));

        // Save locale in cookies
        if ($locale !== $cookieLocale) {
            cookie()->queue(
                config('app.settings.localeCookieName'),
                $locale,
                60 * 24 * 365 // 1 year
            );
        }

        // Set the locale for app
        app()->setLocale($locale);

        return $locale;
    }
}
