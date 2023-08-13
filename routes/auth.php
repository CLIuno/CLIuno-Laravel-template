<?php

use App\Http\Controllers\API\Admin\Auth\AuthController as AdminAuthController;
use App\Http\Controllers\API\User\Auth\AuthController as UserAuthController;
use App\Http\Controllers\API\Admin\AdminController;
use App\Http\Controllers\API\User\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::controller(UserAuthController::class)->group(function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::patch('verify-account', 'doVerifyAccount');
        Route::patch('reset-password', 'doResetPassword');
        Route::post('forget-password', 'forgetPassword');
    });
});

Route::middleware('auth:sanctum')->get('logout', function (Request $request) {
    $request
        ->user()
        ->currentAccessToken()
        ->delete();
    return response()->json(['message' => 'logged out successfully']);
});
