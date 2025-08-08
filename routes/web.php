<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\Operator\HomeController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\Master\ParentsController;
use App\Http\Controllers\Master\ProductsController;
use App\Http\Controllers\Master\StudentsController;
use App\Http\Controllers\Settings\LicenseController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Master\CategoriesController;
use App\Http\Controllers\ActiveTransactionsController;
use App\Http\Controllers\Settings\NavigationController;
use App\Http\Controllers\Settings\PreferenceController;
use App\Http\Controllers\Report\TopupController as ReportTopupController;
use App\Http\Controllers\Report\TransactionsController as ReportTransactionsController;

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

Route::middleware('auth', 'verified')->group(function () {
    /* ---- Dashboard */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::redirect('/', '/dashboard');

    /* ---- Transaksi */
    Route::get('transactions/{transactionId?}', [TransactionsController::class, 'index'])->name('transactions.index');
    Route::post('transactions', [TransactionsController::class, 'store'])->name('transactions.store');
    Route::put('transactions/{transactionId}/store-new-item-in-same-transaction', [TransactionsController::class, 'storeNewItemInSameTransaction'])->name('transactions.store-new-item-in-same-transaction');
    Route::put('transactions/{transactionItemId}/update-quantity', [TransactionsController::class, 'updateQuantity'])->name('transactions.update-quantity');
    Route::put('transactions/{transactionId}/pay', [TransactionsController::class, 'pay'])->name('transactions.pay');
    Route::delete('transactions/{transactionItemId}/delete-item', [TransactionsController::class, 'itemDestroy'])->name('transactions.item-destroy');
    Route::delete('transactions/{transactionId}', [TransactionsController::class, 'destroy'])->name('transactions.destroy');
    Route::get('active-transactions', [ActiveTransactionsController::class, 'index'])->name('active-transactions.index');

    /* ---- Kartu RFID */
    Route::resource('/rfid', RfidController::class)->names('rfid');

    /* ---- Top Up */
    Route::resource('/topup', TopupController::class)->names('topup');

    /* ---- Cek Saldo */
    Route::resource('/balance', BalanceController::class)->names('balance');

    /* ---- Master Data */
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('/categories', CategoriesController::class)->names('categories');
        Route::resource('/products', ProductsController::class)->names('products');
        Route::resource('/students', StudentsController::class)->names('students');
        Route::resource('/parents', ParentsController::class)->names('parents');
    });

    /* ---- Laporan */
    Route::prefix('report')->name('report.')->group(function () {
        // Route::get('/transactions/{studentId?}', [ReportTransactionsController::class, 'index'])->name('transactions.index');
        Route::resource('/transactions', ReportTransactionsController::class)->names('transactions');
        Route::resource('/topup', ReportTopupController::class)->names('topup');
    });

    /* ---- My Profile */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ---- Settings */
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/navs', NavigationController::class);
    Route::resource('/preferences', PreferenceController::class);
    Route::put('/roles/{role}/permissions', [RoleController::class, 'givePermission'])->name('roles.permissions');
});

require __DIR__ . '/auth.php';

// Change Locale Language
Route::get('change-locale/{lang}', [LocaleController::class, 'changeLocale'])->name('change-locale');
Route::get('/activate', [LicenseController::class, 'showActivateForm'])->name('license.activate.form');
Route::post('/activate', [LicenseController::class, 'activate'])->name('license.activate.submit');
Route::post('/deactivate-license', [LicenseController::class, 'deactivate'])->name('license.deactivate'); // opsional

// Jika ingin route untuk cek status (misal debugging):
Route::get('/license-status', [LicenseController::class, 'status'])->name('license.status');



// Route::middleware('auth')->group(function () {
//     Route::resource('home', HomeController::class);
//     Route::prefix('settings')->name('settings.')->group(function () {
//         Route::resource('users', HomeController::class);
//     });
// });
