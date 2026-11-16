<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\StudentLoanController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

// Belum login: Login & Daftar
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('daftar');
    Route::post('/daftar', [AuthController::class, 'register']);
});

// Sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Daftar & pencarian buku: admin dan siswa
    Route::get('/buku', [BookController::class, 'index'])->name('buku.index');

    // Khusus ADMIN
    Route::middleware('role:admin')->group(function () {
        Route::resource('buku', BookController::class)
            ->except(['index', 'show'])->parameters(['buku' => 'book']);
        Route::resource('anggota', MemberController::class)
            ->except('show')->parameters(['anggota' => 'member']);
        Route::resource('transaksi', LoanController::class)
            ->except('show')->parameters(['transaksi' => 'loan']);
        Route::patch('/transaksi/{loan}/kembali', [LoanController::class, 'kembalikan'])
            ->name('transaksi.kembali');
    });

    // Khusus SISWA
    Route::middleware('role:siswa')->group(function () {
        Route::get('/peminjaman', [StudentLoanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman/{book}', [StudentLoanController::class, 'pinjam'])->name('peminjaman.pinjam');
        Route::get('/pengembalian', [StudentLoanController::class, 'pengembalian'])->name('pengembalian.index');
        Route::patch('/pengembalian/{loan}', [StudentLoanController::class, 'kembalikan'])->name('pengembalian.kembali');
    });
});