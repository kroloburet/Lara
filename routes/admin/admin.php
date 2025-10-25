<?php

use App\Http\Controllers\Admin\CreateMaterialViewController;
use App\Http\Controllers\Admin\DashboardViewController;
use App\Http\Controllers\Admin\MenuViewController;
use App\Http\Controllers\Admin\UpdateStaticMaterialViewController;
use App\Http\Controllers\Admin\UpdateMaterialViewController;
use App\Http\Controllers\Admin\MaterialsViewController;
use App\Http\Controllers\Admin\CreateModeratorViewController;
use App\Http\Controllers\Admin\UpdateModeratorViewController;
use App\Http\Controllers\Admin\ModeratorsViewController;
use App\Http\Controllers\Admin\UpdateSecurityViewController;
use App\Http\Controllers\Front\LoginAdminViewController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login',
    LoginAdminViewController::class)
    ->withoutMiddleware(['auth', 'accessMode'])
    ->name('login');
Route::get('/logout', function () {
    auth('admin')->logout();
    return redirect()->back();
})->name('logout');

Route::get('/',
    DashboardViewController::class)->name('dashboard');
Route::get('/update/security',
    UpdateSecurityViewController::class)->name('update.security');
Route::view('/help', 'admin.help')->name('help');

// Moderators
Route::get('/moderators',
    ModeratorsViewController::class)->name('moderators');
Route::get('/create/moderator',
    CreateModeratorViewController::class)->name('create.moderator');
Route::get('/update/moderator/{id}',
    UpdateModeratorViewController::class)
    ->where('id', '[0-9]+')
    ->name('update.moderator');

// Menu
Route::get('/menu', MenuViewController::class)->name('menu');

// Materials
Route::get('/{type}',
    MaterialsViewController::class)->name('materials');
Route::get('/create/{type}/{content_locale}',
    CreateMaterialViewController::class)->name('create.material');
Route::get('/update/{type}/{alias}/{content_locale}',
    UpdateMaterialViewController::class)->name('update.material');
Route::get('/update/{type}/{content_locale}',
    UpdateStaticMaterialViewController::class)->name('update.static-material');
