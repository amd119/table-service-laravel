<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:administrator,waiter,kasir,owner'])->name('dashboard');

Route::middleware(['auth', 'role:administrator,waiter'])->group(function () {
    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::get('/create', [MenuController::class, 'create'])->name('create');
        Route::post('/store', [MenuController::class, 'store'])->name('store');
        // Route::get('/edit/{id}', [MenuController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MenuController::class, 'update'])->name('update');
        Route::delete('/delete', [MenuController::class, 'delete'])->name('delete');
    });
});

Route::middleware(['auth', 'role:administrator'])->group(function () {
    Route::prefix('table')->name('table.')->group(function () {
        Route::get('/', [TableController::class, 'index'])->name('index');
        Route::get('/create', [TableController::class, 'create'])->name('create');
        Route::post('/store', [TableController::class, 'store'])->name('store');
        Route::put('/update/{id}', [TableController::class, 'update'])->name('update');
        Route::delete('/delete', [TableController::class, 'delete'])->name('delete');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete', [UserController::class, 'delete'])->name('delete');
    });
});

Route::middleware(['auth', 'role:waiter,owner'])->group(function () {
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::get('/report', [OrderController::class, 'reportForm'])->name('report.form');
        Route::get('/report/generate', [OrderController::class, 'report'])->name('report');
        Route::get('/show/{idpelanggan}', [OrderController::class, 'show'])->name('show');
        // Route::get('/edit/{idpelanggan}', [OrderController::class, 'edit'])->name('edit');
        Route::post('/store', [OrderController::class, 'store'])->name('store');
        Route::patch('/update-status/{idpesanan}', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::put('/update/{idpelanggan}', [OrderController::class, 'update'])->name('update');
        Route::delete('/delete', [OrderController::class, 'delete'])->name('delete');
    });
});

Route::middleware(['auth', 'role:kasir,owner'])->group(function () {
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/process-payment', [TransactionController::class, 'processPayment'])->name('process-payment');
        Route::post('/store', [TransactionController::class, 'store'])->name('store');
        Route::get('/show/{idtransaksi}', [TransactionController::class, 'show'])->name('show');
        Route::get('/receipt/{idtransaksi}', [TransactionController::class, 'receipt'])->name('receipt');
        Route::get('/report', [TransactionController::class, 'reportForm'])->name('report.form');
        Route::get('/report/generate', [TransactionController::class, 'report'])->name('report');
    });
});

require __DIR__.'/auth.php';