<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\User\Auth\AuthController as UserAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'user'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::post('login', [UserAuthController::class, 'login'])->middleware('throttle:5,2');
        Route::post('register', [UserController::class, 'register']);
        Route::post('forgot-password', [UserAuthController::class, 'forgotPassword']);
        Route::post('reset-password', [UserAuthController::class, 'resetPassword']);
    }
    );

    Route::group(['middleware' => 'auth:user-api'], function () {
        Route::get('all', [UserController::class, 'getUsers']);
        Route::get('{id}', [UserController::class, 'getUser']);
        Route::get('current-user', [UserController::class, 'getCurrentUser']);
        Route::patch('change-password', [UserAuthController::class, 'changePassword']);
    });
});

