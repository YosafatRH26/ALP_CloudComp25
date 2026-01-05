<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\Admin\AdminCvController;
use App\Http\Controllers\Admin\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED & VERIFIED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USER ROUTES (Role: User)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:user')->group(function () {

        // --- DASHBOARD ---
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        // --- PROFILE ---
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // --- CV ANALYSIS FLOW ---
        
        // 1. Form Upload
        Route::get('/cv/upload', [CvController::class, 'create'])->name('cv.create');
        
        // 2. Proses Upload & Analyze
        Route::post('/cv/analyze', [CvController::class, 'store'])->name('cv.store');
        
        // 3. Halaman Hasil (Menggunakan Model Binding {analysis})
        Route::get('/cv/result/{analysis}', [CvController::class, 'result'])->name('cv.result');
        
        // 4. Halaman History
        Route::get('/cv/history', [CvController::class, 'history'])->name('cv.history');
        
        // 5. Hapus History (Menggunakan ID biasa {id})
        Route::delete('/cv/history/{id}', [CvController::class, 'destroyHistory'])->name('cv.history.delete');
        
        // 6. Submit ke Admin (Menggunakan ID biasa {id})
        Route::post('/cv/submit/{id}', [CvController::class, 'submitToAdmin'])->name('cv.push-to-admin');

    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES (Role: Admin)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(function () {

            // --- ADMIN DASHBOARD ---
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // --- CV COMPARISON ---
            Route::get('/cv/compare', [AdminCvController::class, 'compareForm'])->name('cv.compare.form');
            Route::post('/cv/compare', [AdminCvController::class, 'compareResult'])->name('cv.compare');

            // --- VIEW USER CV (ADMIN MODE) ---
            // Kita menggunakan controller yang sama dengan user, tapi route name berbeda
            Route::get('/cv/view/{analysis}', [CvController::class, 'result'])->name('cv.result'); 
        });

});

require __DIR__.'/auth.php';