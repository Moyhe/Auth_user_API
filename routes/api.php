<?php

use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\AuthUserContorller;
use App\Http\Controllers\Auth\ConfirmePasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->group(function () {

    Route::post('/verify', VerifyEmailController::class)->name('verification.send');

    Route::get('verify-email/{id}/{hash}', EmailVerificationController::class)
        ->middleware(['signed'])
        ->name('verification.verify');


    Route::post('confirm-password', ConfirmePasswordController::class)->name('password.confirm');

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthUserContorller::class, 'logout'])->name('logout');
});


Route::middleware('guest')->group(function () {

    Route::post('forgot-password', PasswordResetController::class)->name('password.forget');

    Route::post('reset-password', NewPasswordController::class)->name('password.reset');

    Route::post('/user/register', [AuthUserContorller::class, 'register'])->name('api.register');
    Route::post('/user/login', [AuthUserContorller::class, 'login'])->name('api.login');
});
