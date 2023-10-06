<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\PurchasesController;
use App\Http\Controllers\Api\SalesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->name('api.')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::name('inventories.')->prefix('inventories')->group(function() {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/{id?}', [InventoryController::class, 'show'])->name('show');
        Route::post('/', [InventoryController::class, 'store'])->name('store');
        Route::put('/{id?}', [InventoryController::class, 'update'])->name('update');
        Route::put('/stock/{id?}', [InventoryController::class, 'updateStock'])->name('update.stock');
        Route::delete('/{id?}', [InventoryController::class, 'destroy'])->name('destroy');
    });

    Route::name('sales.')->prefix('sales')->group(function() {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('/{id?}', [SalesController::class, 'show'])->name('show');
        Route::post('/', [SalesController::class, 'store'])->name('store');
        Route::put('/{id?}', [SalesController::class, 'update'])->name('update');
        Route::delete('/{id?}', [SalesController::class, 'destroy'])->name('destroy');
    });

    Route::name('purchases.')->prefix('purchases')->group(function() {
        Route::get('/', [PurchasesController::class, 'index'])->name('index');
        Route::get('/{id?}', [PurchasesController::class, 'show'])->name('show');
        Route::post('/', [PurchasesController::class, 'store'])->name('store');
        Route::put('/{id?}', [PurchasesController::class, 'update'])->name('update');
        Route::delete('/{id?}', [PurchasesController::class, 'destroy'])->name('destroy');
    });
});