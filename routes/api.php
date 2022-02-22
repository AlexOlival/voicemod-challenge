<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthenticationController;

// 'Guest' endpoints
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthenticationController::class, 'login']);
});

// Authenticated endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::put('/update-password', [AuthenticationController::class, 'update']);

    Route::get('/users', [UsersController::class, 'index']);
    Route::post('/users', [UsersController::class, 'store']);
    Route::put('/users/{user}', [UsersController::class, 'update']);
    Route::delete('/users/{user}', [UsersController::class, 'destroy']);
});
