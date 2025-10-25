<?php

use App\Http\Controllers\XHR\BgImageManagerController;
use App\Http\Controllers\XHR\Email\ComplainController;
use App\Http\Controllers\XHR\Email\AppealController;
use App\Http\Controllers\XHR\Front\PaginateMaterialsListController;
use App\Http\Controllers\XHR\Front\PaginateSubMaterialsListController;
use App\Http\Controllers\XHR\Front\ToggleStatisticKeyController;
use App\Http\Controllers\XHR\MediaManagerController;
use App\Http\Controllers\XHR\SetConsumerSettingsController;
use App\Http\Requests\XHR\IsUniqueValueRequest;
use Illuminate\Support\Facades\Route;

// Paginator
Route::post('/paginate/materials/list',
    PaginateMaterialsListController::class);
Route::post('/paginate/sub-materials/list',
    PaginateSubMaterialsListController::class);

// Contact form
Route::post('/appeal/send',
    AppealController::class)
    ->middleware('throttle:3,1')
    ->withoutMiddleware('accessMode')
    ->name('appeal.send');

// Complain form
Route::post('/complain/send',
    ComplainController::class)
    ->middleware('throttle:3,1')
    ->name('complain.send');

// Is unique value request
Route::post('/is-unique-value', function (IsUniqueValueRequest $request) {
    // Only validation is required
});

// Settings & other
Route::post('/set-consumer-settings',
    SetConsumerSettingsController::class)
    ->middleware('throttle:4,1');
Route::post('/toggle-statistic-key',
    ToggleStatisticKeyController::class)
    ->middleware('throttle:4,1');


// Bg image manager
Route::group(
    [
        'as' => 'bg-image.',
        'prefix' => 'bg-image',
        'controller' => BgImageManagerController::class,
    ], function () {
    Route::post('/upload', 'upload');
    Route::post('/delete', 'delete');
});

// Media manager
Route::group(
    [
        'as' => 'media.',
        'prefix' => 'media',
        'controller' => MediaManagerController::class,
    ], function () {
    Route::post('/set', 'set');
    Route::post('/rename', 'rename');
});

