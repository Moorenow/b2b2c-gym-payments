<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'show']);

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:gym_admin|super_admin');

    Route::middleware('role:gym_admin|super_admin')
        ->prefix('admin')
        ->group(function (): void {
            Route::get('roles', [RoleManagementController::class, 'index']);
            Route::post('users/{user}/role', [RoleManagementController::class, 'updateUserRole']);
        });
});
