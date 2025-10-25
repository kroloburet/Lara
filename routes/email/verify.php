<?php

use App\Http\Controllers\Email\EmailVerifyDenyViewController;
use App\Http\Controllers\Email\SendVerifyEmailNoticeController;
use App\Http\Controllers\Email\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// "Verify yore email" page
Route::get('/email/deny',
    EmailVerifyDenyViewController::class)
    ->prefix('{locale?}')
    ->middleware(['addLocalePrefixToUri', 'auth'])
    ->name('email.deny');

// "Your email is successfully verified" page
Route::view('/email/verified', 'verify.verify-email-success')
    ->prefix('{locale?}')
    ->middleware(['addLocalePrefixToUri'])
    ->name('email.verified');

// Verification resend notice execute
Route::post('/email/resend',
    SendVerifyEmailNoticeController::class)
    ->middleware(['throttle:3,1', 'setLocale', 'auth'])
    ->name('email.resend');

// Verification email execute
Route::get('/email/execute/{type}/{token}',
    VerifyEmailController::class)
    ->middleware('signed')
    ->name('email.execute');
