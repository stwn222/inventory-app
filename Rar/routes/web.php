<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;


Route::get('/', function () {
    return Redirect::route('login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::get('/items/{id}/edit-stock', [ItemController::class, 'editStock'])->name('items.edit-stock');
    Route::get('/items/{id}/StockCard', [ItemController::class, 'viewStockCard'])->name('items.StockCard');
    Route::patch('/items/{id}/update-stock', [ItemController::class, 'updateStock'])->name('items.update-stock');
    Route::patch('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::resource('pemasukan', PemasukanController::class);
    Route::resource('pengeluaran', PengeluaranController::class);
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');


    Route::get('/items/print', [ItemController::class, 'printPreview'])->name('items.print');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
});

require __DIR__.'/auth.php';
