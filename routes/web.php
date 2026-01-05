<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\Admin\AdminCvController;
use App\Http\Controllers\Admin\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH + VERIFIED
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:user')->group(function () {

        // Dashboard user
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // CV (user)
        Route::get('/cv', [CvController::class, 'create'])->name('cv.create');
        Route::post('/cv', [CvController::class, 'store'])->name('cv.store');
        Route::get('/cv/{analysis}/result', [CvController::class, 'result'])->name('cv.result');
        Route::get('/cv/history', [CvController::class, 'history'])->name('cv.history');
        Route::delete('/cv/{analysis}/history', [CvController::class, 'destroyHistory'])->name('cv.history.delete');
        Route::post('/cv/{submission}/push-to-admin', [CvController::class, 'submitToAdmin'])
            ->name('cv.push-to-admin');

    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN (wrapped in auth + verified + role:admin)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')
    ->name('admin.')
    ->middleware('role:admin')
    ->group(function () {

        // ✅ STATIC DULU
        Route::get('/cv/compare', [AdminCvController::class, 'compareForm'])
            ->name('cv.compare.form');

        Route::post('/cv/compare', [AdminCvController::class, 'compareResult'])
            ->name('cv.compare');

        // ❗ DYNAMIC PALING BAWAH
        Route::get('/cv/{analysis}', [CvController::class, 'result'])
            ->name('cv.result');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');
    });

});

require __DIR__.'/auth.php';
