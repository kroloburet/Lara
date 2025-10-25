<?php

use App\Http\Controllers\XHR\Admin\CreateModeratorController;
use App\Http\Controllers\XHR\Admin\DeleteBugReportController;
use App\Http\Controllers\XHR\Admin\DeleteMaterialController;
use App\Http\Controllers\XHR\Admin\DeleteModeratorController;
use App\Http\Controllers\XHR\Admin\GetMaterialContentController;
use App\Http\Controllers\XHR\Admin\MaterialSelectorController;
use App\Http\Controllers\XHR\Admin\MenuManagerController;
use App\Http\Controllers\XHR\Admin\PaginateBugReportsListController;
use App\Http\Controllers\XHR\Admin\PaginateFilteredMaterialsListController;
use App\Http\Controllers\XHR\Admin\PaginateFilteredModeratorsListController;
use App\Http\Controllers\XHR\Admin\PaginateUnfinishedMaterialsListController;
use App\Http\Controllers\XHR\Admin\RefreshSitemapController;
use App\Http\Controllers\XHR\Admin\SetAppSettingsController;
use App\Http\Controllers\XHR\Admin\ToggleBlockMaterialController;
use App\Http\Controllers\XHR\Admin\ToggleBlockModeratorController;
use App\Http\Controllers\XHR\Admin\UpdateModeratorController;
use App\Http\Controllers\XHR\Admin\UpdateOrCreateMaterialController;
use App\Http\Controllers\XHR\Admin\UpdateSecurityController;
use App\Http\Controllers\XHR\Front\LoginAdminController;
use App\Http\Controllers\XHR\Front\LoginAdminRecoveryController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::post('/login',
    LoginAdminController::class)
    ->middleware('throttle:3,1')
    ->withoutMiddleware(['accessMode', 'auth'])
    ->name('login');
Route::post('/login/recovery',
    LoginAdminRecoveryController::class)
    ->middleware('throttle:3,1')
    ->withoutMiddleware(['accessMode', 'auth'])
    ->name('login.recovery');

// Moderators
Route::post('/paginate/moderators/list',
    PaginateFilteredModeratorsListController::class)->name('paginate.moderators.list');
Route::post('/create/moderator',
    CreateModeratorController::class)->name('create.moderator');
Route::post('/update/moderator',
    UpdateModeratorController::class)->name('update.moderator');
Route::post('/toggle/block/moderator',
    ToggleBlockModeratorController::class)->name('toggle.block.moderator');
Route::post('/delete/moderator',
    DeleteModeratorController::class)->name('delete.moderator');

// Menu
Route::group(
    [
        'as' => 'menu.',
        'prefix' => 'menu',
        'controller' => MenuManagerController::class,
    ], function () {
    Route::post('/create', 'create');
    Route::post('/update', 'update');
    Route::post('/delete', 'delete');
    Route::post('/toggle', 'toggle');
    Route::post('/refresh', 'refresh');
    Route::post('/order-position-options', 'orderPositionOptions');
    Route::post('/parent-id-options', 'parentIdOptions');
});

// Materials
Route::post('/paginate/unfinished/materials/list',
    PaginateUnfinishedMaterialsListController::class)
    ->name('paginate.unfinished.materials.list');
Route::post('/paginate/materials/list',
    PaginateFilteredMaterialsListController::class)
    ->name('paginate.materials.list');
Route::post('/get/material/content',
    GetMaterialContentController::class);
Route::post('/create/material',
    UpdateOrCreateMaterialController::class)->name('create.material');
Route::post('/update/material',
    UpdateOrCreateMaterialController::class)->name('update.material');
Route::post('/toggle/block/material',
    ToggleBlockMaterialController::class)->name('toggle.block.material');
Route::post('/delete/material',
    DeleteMaterialController::class)->name('delete.material');

Route::group(
    [
        'as' => 'material-selector.',
        'prefix' => 'material-selector',
        'controller' => MaterialSelectorController::class,
    ], function () {
    Route::post('/load-component', 'loadComponent');
    Route::post('/list', 'getList');
});

// Bug Reports
Route::post('/paginate/bug-reports/list',
    PaginateBugReportsListController::class)->name('paginate.bug-reports.list');
Route::post('/delete/bug-report',
    DeleteBugReportController::class)->name('delete.bug-report');

// Settings & other
Route::post('/set/app/setting',
    SetAppSettingsController::class)->name('set.app.setting');
Route::post('/refresh/sitemap',
    [RefreshSitemapController::class, 'refresh'])->name('refresh.sitemap');
Route::post('/view/sitemap',
    [RefreshSitemapController::class, 'view'])->name('view.sitemap');
Route::post('/update/security',
    UpdateSecurityController::class)->name('update.security');
