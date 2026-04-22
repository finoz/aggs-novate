<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\NoticeController as AdminNoticeController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// ─── Sito pubblico ────────────────────────────────────────────────────────────

Route::get('/', [PageController::class, 'home'])->name('home');

// ─── Admin: autenticazione ────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth');

    // ─── Admin: area protetta ─────────────────────────────────────────────────
    Route::middleware('auth')->group(function () {
        Route::get('/', fn () => redirect()->route('admin.pages.index'));
        Route::resource('pages', AdminPageController::class);
        Route::resource('notices', AdminNoticeController::class);
        Route::post('upload', [UploadController::class, 'store'])->name('upload');
    });
});

// ─── Pagine dinamiche (catch-all — deve essere l'ultima route) ────────────────

Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
