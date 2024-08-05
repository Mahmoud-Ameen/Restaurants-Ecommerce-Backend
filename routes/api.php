<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);

Route::middleware('jwt_auth')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/user', [AuthController::class,'user']);
});

Route::middleware(['jwt_auth','jwt_check_admin'])->group(function () {
    Route::post('auth/createAdmin', [AuthController::class,'createAdmin']);
});
