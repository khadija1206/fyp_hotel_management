<?php

use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Receptionist\BookingController;
use App\Http\Controllers\Receptionist\CheckInController;
use App\Http\Controllers\Receptionist\CheckOutController;
use App\Http\Controllers\Receptionist\GuestController;
use App\Http\Controllers\Receptionist\WalkInController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        Route::resource('room-types', RoomTypeController::class)->except(['show']);

        Route::resource('rooms', RoomController::class)->except(['show']);

        Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
    });

Route::middleware(['auth', 'verified', 'role:admin,receptionist'])->group(function () {
    Route::resource('guests', GuestController::class);

    Route::get('bookings/available-rooms', [BookingController::class, 'availableRooms'])->name('bookings.available-rooms');
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::resource('bookings', BookingController::class);

    Route::get('check-in', [CheckInController::class, 'index'])->name('check-in.index');
    Route::post('check-in/{booking}', [CheckInController::class, 'process'])->name('check-in.process');

    Route::get('check-out', [CheckOutController::class, 'index'])->name('check-out.index');
    Route::get('check-out/{booking}', [CheckOutController::class, 'show'])->name('check-out.show');
    Route::post('check-out/{booking}', [CheckOutController::class, 'process'])->name('check-out.process');

    Route::get('walk-in', [WalkInController::class, 'create'])->name('walk-in.create');
    Route::post('walk-in', [WalkInController::class, 'store'])->name('walk-in.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/my-portal', [DashboardController::class, 'index'])->name('guest.dashboard');
    Route::get('/_design-test', function () {
        return view('_design-test');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
