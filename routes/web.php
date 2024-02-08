<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckIsAdmin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RideController as AdminRideController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

Auth::routes(['verify' => true]);

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/rides', [RideController::class, 'index'])->name('rides.index');

// User Profile Routes
Route::name('profile.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/profile/{user}', [ProfileController::class, 'update'])->name('update');
    Route::patch('/profile/{user}/password', [ProfileController::class, 'updatePassword'])->name('update-password');
});

// Booking Routes
Route::name('bookings.')->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('index');
    Route::get('/new-booking/{ride}/{startLocation}/{endLocation}/{date}', [BookingController::class, 'create'])->name('create');
    Route::get('booking/{booking}', [BookingController::class, 'show'])->name('show');
    Route::post('/bookings', [BookingController::class, 'store'])->name('store');
    Route::patch('/bookings/{booking}', [BookingController::class, 'cancel'])->name('cancel');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Location Routes
    Route::resource('locations', LocationController::class)->except(['show']);

    // Route Routes
    Route::resource('routes', RouteController::class);

    // Admin Booking Routes
    Route::name('bookings.')->group(function () {
        Route::get('bookings', [AdminBookingController::class, 'index'])->name('index');
        Route::get('bookings/{booking}', [AdminBookingController::class, 'edit'])->name('edit');
        Route::patch('bookings/{booking}', [AdminBookingController::class, 'update'])->name('update');
        Route::delete('bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('destroy');
        Route::get('/bookings/datatables', [AdminBookingController::class, 'datatables'])->name('datatables');
    });

    // Bus Routes
    Route::resource('buses', BusController::class)->except(['show']);

    // Admin Ride Routes
    Route::name('rides.')->group(function () {
        Route::resource('rides', AdminRideController::class);
        Route::get('rides', [AdminRideController::class, 'index'])->name('index');
        Route::get('rides/create', [AdminRideController::class, 'create'])->name('create');
        Route::post('rides', [AdminRideController::class, 'store'])->name('store');
        Route::get('rides/{ride}', [AdminRideController::class, 'show'])->name('show');
        Route::put('rides/{ride}', [AdminRideController::class, 'update'])->name('update');
        Route::get('rides/{ride}/edit', [AdminRideController::class, 'edit'])->name('edit');
        Route::delete('rides/{ride}', [AdminRideController::class, 'destroy'])->name('destroy');
    });

    // Route Routes
    Route::name('routes.')->group(function () {
        Route::get('routes', [RouteController::class, 'index'])->name('index');
        Route::get('routes/create', [RouteController::class, 'create'])->name('create');
        Route::post('routes', [RouteController::class, 'store'])->name('store');
        Route::get('routes/{route}', [RouteController::class, 'show'])->name('show');
        Route::get('routes/{route}/edit', [RouteController::class, 'edit'])->name('edit');
        Route::patch('routes/{route}', [RouteController::class, 'update'])->name('update');
        Route::delete('routes/{route}', [RouteController::class, 'destroy'])->name('destroy');
    });

    // User Routes
    Route::name('users.')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('users', [UserController::class, 'index'])->name('index');
        Route::get('users/create', [UserController::class, 'create'])->name('create');
        Route::post('users', [UserController::class, 'store'])->name('store');
        Route::get('users/{user}', [UserController::class, 'show'])->name('show');
        Route::put('users/{user}', [UserController::class, 'update'])->name('update');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});

// Additional Admin Routes
Route::get('/admin/buses/{bus}/edit', [BusController::class, 'edit'])->name('admin.buses.edit');
Route::delete('/admin/buses/{bus}', [BusController::class, 'destroy'])->name('admin.buses.destroy');
Route::get('/admin/bookings/datatables', [AdminBookingController::class, 'datatables'])->name('admin.bookings.datatables');
