<?php

namespace App\Http\Middleware;

use App\Services\LocalizationService;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class AddLocalePrefixToUri
{

    /**
     * Set User Local and add it to the beginning of URI
     *
     * @param Request $request
     * @param \Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = app(LocalizationService::class)->setLocale();
        $segment = $request->segment(1);
        $availableLocales = config('app.available_locales', []);

        // Set the default locale for URL
        URL::defaults(['locale' => $locale]);

        // If the URL segment contains a valid locale, send the request further
        if ($segment && in_array($segment, $availableLocales)) {
            return $next($request);
        }

        // Determine if the search bot is (for SEO)
        $isBot = $this->isSearchEngineBot($request);

        // Define a new URL with the user locale
        $newUrl = '/' . $locale . $request->getRequestUri();

        // Use 301 for bots, 302 for users
        $redirectCode = $isBot ? 301 : 302;

        // Keep Flesh Sessions
        session()->reflash();

        return redirect($newUrl, $redirectCode);
    }

    /**
     * Check if the request is from a search engine bot
     *
     * @param Request $request
     * @return bool
     */
    private function isSearchEngineBot(Request $request): bool
    {
        $userAgent = $request->header('User-Agent', '');
        $botPatterns = [
            'Googlebot', 'Bingbot', 'Slurp', 'DuckDuckBot', 'YandexBot', 'Baiduspider', 'Sogou', 'Exabot',
            'AhrefsBot', 'MJ12bot', 'SemrushBot', 'DotBot', 'SeznamBot', 'Coccocbot', 'Applebot',
        ];

        foreach ($botPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }
}
