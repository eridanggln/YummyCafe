<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\DashboardController;
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard.index');
/*
|--------------------------------------------------------------------------
| RIWAYAT
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\RiwayatController;
Route::get('/riwayat-pesanan', [RiwayatController::class, 'index'])->name('riwayat.index');
/*
|--------------------------------------------------------------------------
| USER
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\UserController;

Route::middleware('auth')->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::put('/user/update', [UserController::class, 'update'])->name('user.update');
    Route::patch('/user/{id}/toggle', [UserController::class, 'toggleStatus'])->name('user.toggle');
});

/*
|--------------------------------------------------------------------------
| MENU (MAKANAN & MINUMAN)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuOpsiController; // Pindah ke atas agar rapi

Route::middleware('auth')->group(function () {

    // LIST
    Route::get('/menu/makanan', [MenuController::class, 'indexMakanan'])->name('menu.makanan');
    Route::get('/menu/minuman', [MenuController::class, 'indexMinuman'])->name('menu.minuman');
    Route::get('/menu/snack', [MenuController::class, 'indexSnack'])->name('menu.snack');
    Route::get('/menu/dessert', [MenuController::class, 'indexDessert'])->name('menu.dessert');

    // CREATE & STORE
    Route::get('/menu/create/{kategori}', [MenuController::class, 'create'])->name('menu.create');
    Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store');

    // UPDATE MENU & ADD ON (Diarahkan ke MenuOpsiController karena function update ada di sana)
    // Menggunakan PUT agar sesuai dengan @method('PUT') di modal edit Anda
    Route::put('/menu/update', [MenuOpsiController::class, 'update'])->name('menu.update');

    // TOGGLE STATUS MENU
    Route::put('/menu/{id}/toggle', [MenuController::class, 'toggleStatus'])->name('menu.toggle');

    /* --- MENU OPSI (ADD ON) --- */
    Route::post('/menu/opsi/store', [MenuOpsiController::class, 'store'])->name('menu.opsi.store');
    Route::get('/menu/{id}/opsi', [MenuOpsiController::class, 'getByMenu'])->name('menu.opsi.get');
    Route::put('/menu/opsi/pilihan/{id_opsi_detail}/toggle', [MenuOpsiController::class, 'togglePilihan'])->name('menu.opsi.pilihan.toggle');
});

/*
|--------------------------------------------------------------------------
| PESANAN & LAINNYA
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\PesananController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

Route::middleware('auth')->group(function () {
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/export-history', [OrderController::class, 'exportExcel'])->name('export.history');
    Route::get('/order/print/{order_number}', [OrderController::class, 'printStruk'])->name('order.print');
});

Route::prefix('payment')->middleware(['auth'])->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/store', [PaymentController::class, 'store'])->name('payment.store');
    Route::put('/update', [PaymentController::class, 'update'])->name('payment.update');
    Route::patch('/payment/{id}/toggle', [PaymentController::class, 'toggle'])->name('payment.toggle');
});