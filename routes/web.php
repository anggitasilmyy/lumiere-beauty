<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PaymentReceiptController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TreatmentController as AdminTreatmentController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;

use App\Http\Controllers\ReviewController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth') //digunakan agar user yang belum login tidak bisa melakukan logout.
    ->name('logout');

Route::get('/treatments', [TreatmentController::class, 'index'])
    ->name('treatments.index');

Route::get('/promotions', [PromotionController::class, 'index'])
    ->name('promotions.index');

Route::middleware('auth')->group(function () {
    Route::post('/book-treatment', [CheckoutController::class, 'storePending'])
        ->name('checkout.store');

    Route::get('/treatment-payment/{token}', [CheckoutController::class, 'payment'])
        ->name('checkout.payment');

    Route::post('/confirm-treatment-payment', [CheckoutController::class, 'confirm'])
        ->name('checkout.confirm');

    Route::get('/my-bookings', [BookingController::class, 'myBookings'])
        ->name('bookings.mine');

    Route::get('/my-points', [PointController::class, 'index'])
        ->name('points.index');

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');

    Route::get('/payment-receipt/{booking}', [PaymentReceiptController::class, 'show'])
        ->name('payment.receipt');

    Route::post('/reviews', [ReviewController::class, 'store'])
        ->name('reviews.store');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.') 
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('/treatments', AdminTreatmentController::class);

        Route::patch('/treatments/{treatment}/toggle-status', [AdminTreatmentController::class, 'toggleStatus'])
            ->name('treatments.toggle-status');

        Route::get('/bookings', [AdminBookingController::class, 'index'])
            ->name('bookings.index');

        Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])
            ->name('bookings.update-status');

        Route::resource('/promotions', AdminPromotionController::class)
                ->only(['index', 'store', 'update', 'destroy']);// only digunakan agar hanya route index, store, update, dan destroy yang dibuat.
    });