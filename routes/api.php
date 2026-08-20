<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(fn() => [
  Route::apiResource('cats', CatController::class)->only(['store', 'update', 'destroy']),
  Route::apiResource('users', UserController::class),
  Route::apiResource('basket', BasketController::class),
  Route::apiResource('orders', OrderController::class),
  Route::apiResource('transactions', TransactionController::class),
  Route::post('logout', [AuthController::class, 'logout'])
]);

Route::apiResource('cats', CatController::class)->only(['index', 'show']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
