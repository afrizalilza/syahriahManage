<?php

use App\Http\Controllers\BiayaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\TunggakanController;
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

Route::middleware(['auth', 'pending', 'role:admin,bendahara', 'verified'])->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');

    // Notification Routes
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notification.index');
        Route::get('/notifications/{id}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notification.markAsRead');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Data Master
    Route::get('/santri', [SantriController::class, 'index'])->name('santri.index');
    Route::resource('santri', SantriController::class)->except(['index']);
    Route::get('/biaya', [BiayaController::class, 'index'])->name('biaya.index');
    Route::resource('biaya', BiayaController::class)->except(['index']);

    // Hak Bebas Biaya Santri
    Route::resource('santri_biaya_bebas', App\Http\Controllers\SantriBiayaBebasController::class)->except(['show']);

    // Transaksi
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::resource('pembayaran', PembayaranController::class)->except(['index']);
    Route::get('/tunggakan', [TunggakanController::class, 'index'])->name('tunggakan.index');
    Route::get('tunggakan/detail', [App\Http\Controllers\TunggakanController::class, 'show'])->name('tunggakan.detail');
    Route::get('/tunggakan/export-excel', [TunggakanController::class, 'exportExcel'])->name('tunggakan.exportExcel');
    Route::get('/tunggakan/export-pdf', [App\Http\Controllers\TunggakanController::class, 'exportPdf'])->name('tunggakan.exportPdf');

    // AJAX route for filtering biaya by santri
    Route::get('ajax/biaya-by-santri', [App\Http\Controllers\AjaxController::class, 'getAvailableBiayaForSantri'])->name('ajax.biaya_by_santri');
    Route::get('/ajax/available-biaya', [\App\Http\Controllers\AjaxController::class, 'getAvailableBiayaForPembayaran']);

    // Kas routes
    Route::get('/kas', [App\Http\Controllers\KasController::class, 'index'])->name('kas.index');
    Route::get('/kas/{id}', [App\Http\Controllers\KasController::class, 'detail'])->name('kas.detail');
    Route::get('/kas/{kas}/pengeluaran/create', [App\Http\Controllers\KasController::class, 'createPengeluaran'])->name('kas.pengeluaran.create');
    Route::post('/kas/{kas}/pengeluaran', [App\Http\Controllers\KasController::class, 'storePengeluaran'])->name('kas.pengeluaran.store');
    Route::delete('/kas/{kas}/pengeluaran/{pengeluaran}', [App\Http\Controllers\KasController::class, 'destroyPengeluaran'])->name('kas.pengeluaran.destroy');
    Route::get('/kas/pengeluaran/edit/{pengeluaran}', [App\Http\Controllers\KasController::class, 'editPengeluaran'])->name('kas.pengeluaran.edit');
    Route::put('/kas/pengeluaran/update/{pengeluaran}', [App\Http\Controllers\KasController::class, 'updatePengeluaran'])->name('kas.pengeluaran.update');

    // Laporan
    Route::get('/laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
    Route::get('/laporan/bulanan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.bulanan.export-excel');
    Route::get('/laporan/bulanan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.bulanan.export-pdf');
    Route::get('/laporan/tahunan', [LaporanController::class, 'tahunan'])->name('laporan.tahunan');
    Route::get('/laporan/tahunan/export-excel', [App\Http\Controllers\LaporanController::class, 'exportTahunanExcel'])->name('laporan.tahunan.export-excel');
    Route::get('/laporan/tahunan/export-pdf', [App\Http\Controllers\LaporanController::class, 'exportTahunanPdf'])->name('laporan.tahunan.export-pdf');

    // About & Contact
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
});

Route::get('/pending-approval', function () {
    return view('auth.pending-approval');
})->name('pending.approval');

Route::get('/', function () {
    return redirect()->route('login');
});

// User Management (hanya untuk admin)
Route::middleware(['auth', 'pending', 'role:admin'])->group(function () {
    Route::get('/user/pending', [\App\Http\Controllers\UserManagementController::class, 'pending'])->name('user.pending');
    Route::post('/user/approve/{id}', [\App\Http\Controllers\UserManagementController::class, 'approve'])->name('user.approve');
});

// Google Socialite Routes
Route::get('/auth/google/redirect', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleGoogleCallback'])->name('google.callback');

require __DIR__.'/auth.php';
