<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\CategoriesController;
use App\Http\Controllers\Master\ParentsController;
use App\Http\Controllers\Master\ProductsController;
use App\Http\Controllers\Master\StudentsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\Operator\HomeController;
use App\Http\Controllers\Report\TopupController as ReportTopupController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\NavigationController;
use App\Http\Controllers\Settings\PreferenceController;
use App\Http\Controllers\Report\TransactionsController as ReportTransactionsController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\TopupController;

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
    Route::resource('/transactions', TransactionsController::class)->names('transactions');

    /* ---- Kartu RFID */
    Route::resource('/rfid', RfidController::class)->names('rfid');

    /* ---- Top Up */
    Route::resource('/topup', TopupController::class)->names('topup');

    /* ---- Master Data */
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('/categories', CategoriesController::class)->names('categories');
        Route::resource('/products', ProductsController::class)->names('products');
        Route::resource('/students', StudentsController::class)->names('students');
        Route::resource('/parents', ParentsController::class)->names('parents');
    });

    /* ---- Laporan */
    Route::prefix('report')->name('report.')->group(function () {
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


// Route::middleware('auth')->group(function () {
//     Route::resource('home', HomeController::class);
//     Route::prefix('settings')->name('settings.')->group(function () {
//         Route::resource('users', HomeController::class);
//     });
// });
