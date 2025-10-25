<?php

use App\Http\Controllers\Front\MaterialViewController;
use App\Http\Controllers\Front\StaticMaterialViewController;
use Illuminate\Support\Facades\Route;

Route::get('/',
    [StaticMaterialViewController::class, 'home'])->name('home');
Route::get('/contact',
    [StaticMaterialViewController::class, 'contact'])->name('contact');

// Only Dynamic Materials
Route::get('/{type}', function (string $locale, string $type) {
    $isStatic = config("app.materials.types.{$type}.static");
    abort_if($isStatic, 404);
    return view('front.materials', compact('type'));
})->whereIn('type', array_keys(config('app.materials.types')))
    ->name('materials');

Route::get('/category/{alias}',
    [MaterialViewController::class, 'category'])->name('category');
Route::get('/page/{alias}',
    [MaterialViewController::class, 'page'])->name('page');
