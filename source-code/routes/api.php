<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(
    function () {
        Route::group(
            ['prefix' => 'user'],
            function () {
                Route::get('/', [UserController::class, 'getUser']);
                Route::put('/', [UserController::class, 'updateUser']);
                Route::post('/', [UserController::class, 'updateSettings']);
                Route::post('/verification-settings', [UserController::class, 'verificationSettings']);
            }
        );
        Route::delete('sign-out', [UserController::class, 'signOut']);
    }
);

Route::post('sign-up', [UserController::class, 'signUp']);
Route::post('sign-in', [UserController::class, 'signIn']);
