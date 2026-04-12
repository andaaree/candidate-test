<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LayupController;
use App\Http\Controllers\LayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/supplier/export', [SupplierController::class, 'export'])->name('supplier.export');
    Route::prefix('/supplier/{supplier}/import')->group(function () {
        Route::get('/', [SupplierController::class, 'import'])->name('supplier.import');
        // Route::post('/', [SupplierController::class, 'upload']);
        Route::post('/', [SupplierController::class, 'importFile']);
        // Route::post('/file', [SupplierController::class, 'uploadFile']);
        // Route::get('/review/{sessionId}', [SupplierController::class, 'review']);
        // Route::post('/resolve/{sessionId}', [SupplierController::class, 'resolve']);
        // Route::post('/commit/{sessionId}', [SupplierController::class, 'commit']);
    });
    Route::get('/supplier/{supplier}/import-conflicts', [SupplierController::class, 'getImportConflicts'])->name('supplier.conflict');
    Route::post('/supplier/{supplier}/resolve-conflict', [SupplierController::class, 'resolveConflict']);
    Route::get('/supplier/{supplier}/export', [LayupController::class, 'export'])->name('layup.export');
    Route::resource('supplier',SupplierController::class)->names('supplier');
    Route::resource('supplier.layup',LayupController::class)->names('layup');
    Route::resource('layup.layer',LayerController::class)->names('layer');
});


require __DIR__.'/auth.php';
