<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DisplayController;
use App\Http\Controllers\Api\QueueController;
use App\Http\Controllers\Api\StaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Auth (login)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// Queue (public – for kiosk)
Route::prefix('queues')->group(function () {
    Route::post('/reservation', [QueueController::class, 'createReservation']);
    Route::post('/walkin', [QueueController::class, 'createWalkin']);
    Route::get('/waiting', [QueueController::class, 'getWaiting']);
    Route::get('/{queueNumber}', [QueueController::class, 'getByNumber']);
});

// Display (public)
Route::prefix('display')->group(function () {
    Route::get('/current', [DisplayController::class, 'current']);
    Route::get('/latest', [DisplayController::class, 'latest']);
});

// Dashboard (public monitoring)
Route::get('/dashboard', [DashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Authentication)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Auth (logout, me)
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // Staff operations
    Route::prefix('staff')->group(function () {
        // Duty management
        Route::post('/start-duty', [StaffController::class, 'startDuty']);
        Route::post('/end-duty', [StaffController::class, 'endDuty']);
        Route::get('/counters', [StaffController::class, 'checkCounters']);

        // Queue handling
        Route::post('/call-next', [StaffController::class, 'callNext']);
        Route::post('/complete-current', [StaffController::class, 'completeCurrent']);
        Route::get('/current-info', [StaffController::class, 'getCurrentInfo']);
        Route::post('/add-walkin', [StaffController::class, 'addWalkin']);
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
});
