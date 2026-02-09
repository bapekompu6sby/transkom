<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LayoutsUserController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DriverUIController;

Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('/');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('user')->group(function () {
        Route::get('/dashboard', [LayoutsUserController::class, 'dashboard'])->name('user.dashboard');
        Route::post('/trips', [TripController::class, 'store'])->name('user.trips.store');
    });

    Route::get('/history', [HistoryController::class, 'index'])->name('user.history.index');
});

Route::get('/login-driver', [DriverUIController::class, 'showLoginForm'])->name('login.driver');
Route::post('/login-driver', [DriverUIController::class, 'login'])->name('login.driver.submit');
Route::middleware('auth:driver')->prefix('driver')->group(function () {
    Route::get('/dashboard', [DriverUIController::class, 'dashboard'])->name('driver.dashboard');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
    });

    Route::prefix('cars')->group(function () {
        Route::get('/', [CarController::class, 'index'])->name('admin.cars.index');
        Route::post('/', [CarController::class, 'store'])->name('admin.cars.store');
        Route::put('/{car}', [CarController::class, 'update'])->name('admin.cars.update');
        Route::delete('/{car}', [CarController::class, 'destroy'])->name('admin.cars.destroy');
    });

    Route::prefix('drivers')->group(function () {
        Route::get('/', [DriverController::class, 'index'])->name('admin.drivers.index');
        Route::post('/', [DriverController::class, 'store'])->name('admin.drivers.store');
        Route::put('/{driver}', [DriverController::class, 'update'])->name('admin.drivers.update');
        Route::delete('/{driver}', [DriverController::class, 'destroy'])->name('admin.drivers.destroy');

        Route::post('/toggle-status/{driver}', [DriverController::class, 'toggleStatus'])->name('admin.drivers.toggleStatus');
    });

    Route::prefix('trips')->group(function () {
        Route::get('/', [TripController::class, 'index'])->name('admin.trips.index');
        Route::put('/{trip}', [TripController::class, 'update'])->name('admin.trips.update');
        Route::delete('/{trip}', [TripController::class, 'destroy'])->name('admin.trips.destroy');
        Route::get('/check', [TripController::class, 'checkNew'])->name('admin.trips.check');
    });
});


require __DIR__ . '/auth.php';
