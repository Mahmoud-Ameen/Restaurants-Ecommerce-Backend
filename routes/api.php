<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

//*----<< Authentication and User Endpoints >>----*//

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Must be authenticated
    Route::middleware('JWTAuthenticate')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class,'user']);
    });

    // Must be an admin
    Route::middleware(['JWTAuthenticate','JWTAuthorize.admin'])->group(function () {
        Route::post('/createAdmin', [AuthController::class,'createAdmin']);
        Route::post('/createRestaurantAdmin', [AuthController::class,'createRestaurantAdmin']);
    });

});

//*----<< Restaurants Endpoints >>----*//

Route::prefix('restaurants')->group(function () {
    // create new restaurant
    Route::middleware(['JWTAuthenticate','JWTAuthorize.admin'])->group(function () {
        Route::post('/', [RestaurantController::class, 'store']);
    });
    // get all restaurants
    Route::get('/', [RestaurantController::class, 'index']);

    Route::get('/search', [RestaurantController::class, 'search']);
});



//*----<< Menus Endpoints >>----*//

Route::prefix('menus')->group(function () {
    Route::get('/', [MenuController::class, 'getMenus']);
    Route::get('/{id}', [MenuController::class, 'getMenu']);
    Route::middleware(['JWTAuthenticate','JWTAuthorize.restaurantAdmin'])->group(function () {
        Route::post('/', [MenuController::class, 'createMenu']);
        Route::put('/{id}', [MenuController::class, 'updateMenu']);
    });
    Route::middleware(['JWTAuthenticate','JWTAuthorize.adminOrRestaurantAdmin'])->group(function () {
        Route::delete('/{id}', [MenuController::class, 'deleteMenu']);
    });
});


//*----<< Menu Items Endpoints >>----*//
Route::prefix('/menu-items')->group(function () {
    Route::get('/', [MenuItemController::class, 'getMenuItems']); // get items of menu by (menu id)
    Route::get('/{id}', [MenuItemController::class, 'getSingleMenuItem']); // get a single item
    Route::middleware(['JWTAuthenticate','JWTAuthorize.restaurantAdmin'])->group(function () {
        Route::post('/', [MenuItemController::class, 'createMenuItem'])->middleware(['JWTAuthenticate',"JWTAuthorize.restaurantAdmin"]);
        Route::put('/{id}', [MenuItemController::class, 'updateMenuItem'])->middleware(['JWTAuthenticate',"JWTAuthorize.restaurantAdmin"]);
    });
    Route::delete('/{id}', [MenuItemController::class, 'deleteMenuItem'])->middleware(['JWTAuthenticate',"JWTAuthorize.adminOrRestaurantAdmin"]);
});
