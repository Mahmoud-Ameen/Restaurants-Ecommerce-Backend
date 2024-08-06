<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;

//*----<< Authentication and User Endpoints >>----*//

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Must be authenticated
    Route::middleware('jwt_auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class,'user']);
    });

    // Must be an admin
    Route::middleware(['jwt_auth','jwt_check_admin'])->group(function () {
        Route::post('/createAdmin', [AuthController::class,'createAdmin']);
        Route::post('/createRestaurantAdmin', [AuthController::class,'createRestaurantAdmin']);
    });

});

//*----<< Restaurants Endpoints >>----*//

Route::prefix('restaurants')->group(function () {
    Route::middleware(['jwt_auth','jwt_check_admin'])->post('/', [RestaurantController::class, 'store']); // create store
    Route::get('/', [RestaurantController::class, 'index']); // get all restaurants
    Route::get('/{id}', [RestaurantController::class, 'search']);
});
