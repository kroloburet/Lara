<?php

use App\Http\Controllers\Front\LoginRecoveryController;
use App\Http\Controllers\XHR\BugReportController;
use App\Http\Controllers\XHR\GetConsumersActivityDataController;
use App\Http\Controllers\XHR\PulseController;
use EdSDK\FlmngrServer\FlmngrServer;
use Illuminate\Support\Facades\Route;


/**
 * Recovery login data
 */
Route::get('/recovery/{token}',
    LoginRecoveryController::class)
    ->middleware('signed')
    ->name('recovery.execute');


/**
 * Route for files upload from file manager
 */
Route::post('/flmngr', function () {
    if (! isAdminCheck()) abort(403);

    FlmngrServer::flmngrRequest(
        array(
            'dirFiles' => public_path('uploads/files')
        )
    );
})->name('files');



/**
 * Email verification
 */
Route::group(
    [
        'as' => 'verify.',
        'prefix' => 'verify',
    ], function () {
    require_once base_path('routes/email/verify.php');
});



/**
 * Localized routes
 *
 * In this group, you need to define the user's language,
 * set the application locale and add it to the first segment of route
 */
Route::group(
    [
        'prefix' => '{locale?}',
        'middleware' => ['addLocalePrefixToUri', 'refreshActivity', 'accessMode'],
    ], function () {

    // Public material routes
    require_once base_path('routes/front/material.php');

    // Admin routes
    Route::group(
        [
            'as' => 'admin.',
            'prefix' => 'admin',
            'middleware' => ['auth'],
        ], function () {
        require_once base_path('routes/admin/admin.php');
    })->withoutMiddleware('accessMode');

    /**
     * This is for the correct addition
     * of locale prefix in this group
     */
    Route::fallback(fn() => abort(404));
});


/**
 * XHR requests routs
 *
 * In this group you need to define the user's language
 * and set the application locale
 */
Route::group(
    [
        'as' => 'xhr.',
        'prefix' => 'xhr',
        'middleware' => ['setLocale', 'refreshActivity', 'accessMode'],
    ], function () {

    // XHR public front routes
    require_once base_path('routes/xhr/front/front.php');

    // XHR Admin routes
    Route::group(
        [
            'as' => 'admin.',
            'prefix' => 'admin',
            'middleware' => ['auth'],
        ], function () {
        require_once base_path('routes/xhr/admin/admin.php');
    })->withoutMiddleware('accessMode');

    // Check whether the Consumer session has ended and synchronising data in runtime
    Route::post('/pulse',
        PulseController::class)
        ->withoutMiddleware(['setLocale', 'refreshActivity'])
        ->name('pulse');

    // Check Consumers activity (online / offline)
    Route::post('/get-consumers-activity',
        GetConsumersActivityDataController::class)
        ->middleware(['throttle:60,1']) // 60 Requests per minute
        ->withoutMiddleware(['refreshActivity']);

    // User bug report
    Route::post('bug-report',
        BugReportController::class)
        ->withoutMiddleware(['setLocale', 'accessMode'])
        ->middleware('throttle:2,1')
        ->name('bug-report');
});
