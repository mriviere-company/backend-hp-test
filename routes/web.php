<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

//List Orders
Route::get('/api/orders', [OrderController::class, 'list']);

// Create order
Route::post('/api/order', [OrderController::class, 'add'])->withoutMiddleware([VerifyCsrfToken::class]);

// Update Order
Route::put('/api/order/{id}', [OrderController::class, 'update'])->withoutMiddleware([VerifyCsrfToken::class]);

// Get Order
Route::get('/api/get-order/{id}', [OrderController::class, 'get']);

// Delete Order
Route::delete('/api/order/{id}', [OrderController::class, 'delete'])->withoutMiddleware([VerifyCsrfToken::class]);

// Time of command
Route::get('/api/schedule', [ScheduleController::class, 'calculateSchedule']);

// Get all Products
Route::get('/api/products', [ProductController::class, 'get']);

// Homepage
Route::get('/', [HomeController::class, 'index']);
