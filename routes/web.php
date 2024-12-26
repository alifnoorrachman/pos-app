<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// Route untuk guest/public
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (perlu login)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('barang', BarangController::class);
        Route::resource('users', UserController::class);
    });

    // Admin & Kasir Routes
    Route::middleware('role:admin,kasir')->group(function () {
        Route::resource('transaksi', TransaksiController::class);
    });
});

Route::middleware(['auth'])->group(function () {
    // Route untuk dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route untuk admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('barang', BarangController::class);
        Route::resource('users', UserController::class);
    });

    // Route untuk admin dan kasir
    Route::middleware(['role:admin,kasir'])->group(function () {
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/transaksi/{transaksi}/print', [TransaksiController::class, 'print'])->name('transaksi.print');
    });
});
