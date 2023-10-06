<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SaleController;

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

Route::get('/', function () { // ceritanya bisa jadi landingpage
    return redirect()->route('dashboard');
});

Route::get('/home', function () { // ceritanya bisa jadi landingpage
    return redirect()->route('dashboard');
});

Route::middleware(['guest'])->group(function () {
    Route::prefix('/signin')->controller(AuthController::class)->name('auth.')->group(function () {
        Route::get('/', 'signin')->name('signin');
        Route::post('/', 'signin_action')->name('signin_action');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/signout', [AuthController::class, 'signout'])->name('auth.signout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('inventories.')->prefix('inventories')->group(function() {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/add', [InventoryController::class, 'add'])->name('add');
        Route::get('/update/{inventory?}', [InventoryController::class, 'update'])->name('update');
    });

    Route::name('sales.')->prefix('sales')->group(function() {
        Route::get('/details/{sale?}', [SaleController::class, 'view'])->name('view');
        Route::get('/', [SaleController::class, 'index'])->name('index');
        Route::get('/add', [SaleController::class, 'add'])->name('add');
        Route::get('/update/{sale?}', [SaleController::class, 'update'])->name('update');
    });
});
