<?php

use App\Http\Controllers\AuthUserContorller;
use Illuminate\Http\Request;
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



Route::middleware('auth:api')->get('/users', function () {
    return auth()->user();
});


Route::post('/user/register', [AuthUserContorller::class, 'register'])->name('api.register');
Route::post('/user/login', [AuthUserContorller::class, 'login'])->name('api.login');
