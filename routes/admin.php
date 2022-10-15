<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('role', RoleController::class);
    Route::resource('permission', PermissionController::class);
    Route::resource('user', UserController::class);
    Route::resource('category', CategoryController::class);

    Route::post('role/attach-permission', [RoleController::class, 'attachPermission'])->name('role.attach-permission');
    Route::post('role/detach-permission', [RoleController::class, 'detachPermission'])->name('role.detach-permission');
});

require __DIR__ . '/auth.php';
