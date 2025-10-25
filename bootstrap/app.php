<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'verifiedEmail' => \App\Http\Middleware\VerifiedEmail::class,
            'setLocale' => \App\Http\Middleware\SetLocale::class,
            'addLocalePrefixToUri' => \App\Http\Middleware\AddLocalePrefixToUri::class,
            'accessMode' => \App\Http\Middleware\AccessMode::class,
            'refreshActivity' => \App\Http\Middleware\RefreshActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        /**
         * This intercepts the bugs with the codes specified in the configuration
         * of app.bug_report_status_codes
         */
        $handler = new \App\Exceptions\BugReportHandler();
        $exceptions->reportable(function (Throwable $exception) use ($handler) {
            $handler->report($exception);
        });
        $exceptions->renderable(function (Throwable $exception, $request) use ($handler) {
            return $handler->render($request, $exception);
        });
    })->create();
