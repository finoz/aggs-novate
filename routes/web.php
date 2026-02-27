<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AvvisoController as AdminAvvisoController;
use App\Http\Controllers\AvvisoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// ─── Sito pubblico ────────────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/chi-siamo', [PageController::class, 'chiSiamo'])->name('chi-siamo');
Route::get('/contatti', [PageController::class, 'contatti'])->name('contatti');

Route::get('/avvisi', [AvvisoController::class, 'index'])->name('avvisi.index');
Route::get('/avvisi/{avviso}', [AvvisoController::class, 'show'])->name('avvisi.show');

// ─── Admin: autenticazione ────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth');

    // ─── Admin: area protetta ─────────────────────────────────────────────────
    Route::middleware('auth')->group(function () {
        Route::get('/', fn () => redirect()->route('admin.avvisi.index'));
        Route::resource('avvisi', AdminAvvisoController::class);
    });
});
