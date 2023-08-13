<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\Admin\Auth\AuthController as AdminAuthController;
use App\Http\Controllers\API\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
*/
// Admin Routes
Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:5,2');
});
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin-api']], function () {
    // Admin Controller
    Route::controller(AdminController::class)->group(function () {
        Route::get('current-admin', 'getCurrentAdmin');
        Route::put('update-profile', 'updateProfile');

        // User Routes
        Route::group(['prefix' => 'user'], function () {
            Route::put('{id}', 'updateUser');
            Route::delete('{id}', 'removeUser');
        });

        //  Admin Routes
        Route::group(['middleware' => 'admin'], function () {
            Route::post('create', 'create');
            Route::put('{id}', 'updateAdmin');
            Route::delete('{id}', 'removeAdmin');
        });
    });

    Route::patch('change-password', [AdminAuthController::class, 'changePassword']);


});
