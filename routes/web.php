<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\Admin\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    /* --- USER ROUTES --- */
    Route::middleware('role:user')->group(function () {
        Route::get('/dashboard', function () { 
            return view('dashboard'); 
        })->name('dashboard');
        
        // Profile Management
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // CV Analysis Flow
        Route::get('/cv/upload', [CvController::class, 'create'])->name('cv.create');
        Route::post('/cv/analyze', [CvController::class, 'store'])->name('cv.store');
        Route::get('/cv/result/{analysis}', [CvController::class, 'result'])->name('cv.result');
        Route::get('/cv/history', [CvController::class, 'history'])->name('cv.history');
        Route::delete('/cv/history/{id}', [CvController::class, 'destroyHistory'])->name('cv.history.delete');
        Route::post('/cv/submit/{id}', [CvController::class, 'submitToAdmin'])->name('cv.push-to-admin');
    });

    /* --- ADMIN ROUTES --- */
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(function () {
            
            // 1. DASHBOARD UTAMA
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // 2. PEMILIHAN KANDIDAT (Compare Selection)
            Route::get('/cv/compare-selection', [AdminDashboardController::class, 'compareSelection'])->name('cv.selection');
            
            // 3. PROSES BANDINGKAN (Action POST)
            // Ini rute yang dipanggil saat tombol "Compare Now" diklik
            Route::post('/cv/compare', [AdminDashboardController::class, 'compare'])->name('cv.compare');

            // 4. PREVENT 404 (Action GET)
            // Jika user me-refresh halaman hasil, mereka akan dilempar balik ke pemilihan, bukan Error 404
            Route::get('/cv/compare', function() {
                return redirect()->route('admin.cv.selection');
            });

            // 5. LIHAT DETAIL ANALISIS
            Route::get('/cv/view/{analysis}', [CvController::class, 'result'])->name('cv.result'); 
        });
});

require __DIR__.'/auth.php';