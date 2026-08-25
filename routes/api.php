<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(fn() => [
  Route::middleware('auth:sanctum')->group(fn() => [
    Route::apiResource('cats', CatController::class)->only(['store', 'update', 'destroy']),
    Route::apiResource('users', UserController::class),
    Route::apiResource('basket', BasketController::class),
    Route::apiResource('orders', OrderController::class),
    Route::post('auth/logout', [AuthController::class, 'logout'])
  ]),
  Route::prefix('auth')->group(fn() => [
    Route::post('register', [AuthController::class, 'register']),
    Route::post('login', [AuthController::class, 'login']),
  ]),
  Route::apiResource('cats', CatController::class)->only(['index', 'show']),
]);
