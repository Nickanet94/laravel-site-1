<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/reset', [AuthController::class, 'showReset'])->name('reset');
Route::post('/reset', [AuthController::class, 'reset']);
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('web')->group(function () {
    // Auth routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/doctors', [DoctorController::class, 'index']);
        Route::get('/doctors/create', [DoctorController::class, 'create']);
        Route::post('/doctors/create', [DoctorController::class, 'store']);
        Route::post('/doctors', [DoctorController::class, 'store'])->name('admin.doctors.store');
        Route::delete('/doctors/{id}', [DoctorController::class, 'destroy']);
    });
    
    // User routes
    Route::prefix('user')->group(function () {
        Route::get('/appointment', function() {
            return File::get(public_path('user/appointment.php'));
        });
        Route::post('/make-appointment', [DoctorController::class, 'makeAppointment']);
    });
});


