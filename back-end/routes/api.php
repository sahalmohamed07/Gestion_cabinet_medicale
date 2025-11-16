<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


// Public routes
Route::get('/ping', fn() => ['status' => 'ok', 'app' => 'al_firdaws_api']);

Route::post('/login', [AuthController::class, 'login']);


//Authenticated routes + protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/users/{user}', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Users (admin + user selon règles)
Route::get('/users',           [UserController::class, 'index']);   // admin only
Route::get('/users/{user}',    [UserController::class, 'show']);    // admin ou lui-même
Route::put('/users/{user}',    [UserController::class, 'update']);  // admin ou lui-même
Route::patch('/users/{user}',  [UserController::class, 'update']);  // idem
Route::delete('/users/{user}', [UserController::class, 'destroy']); // admin 
