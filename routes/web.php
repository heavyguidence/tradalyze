<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TradesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiaryController;

// Home Route (Public landing page)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('home');
})->name('home');

// Authentication Routes (Public)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout Route (Authenticated)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Require Authentication)
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/trades', [TradesController::class, 'index'])->name('trades');
    Route::get('/trades/create', [TradesController::class, 'create'])->name('trades.create');
    Route::post('/trades', [TradesController::class, 'store'])->name('trades.store');
    Route::post('/trades/manual', [TradesController::class, 'storeManual'])->name('trades.store.manual');
    Route::post('/trades/save-broker-credentials', [TradesController::class, 'saveBrokerCredentials'])->name('trades.save-broker-credentials');
    Route::post('/trades/auto-import', [TradesController::class, 'autoImport'])->name('trades.auto-import');
    Route::get('/trades/{position}', [TradesController::class, 'show'])->name('trades.show');
    Route::patch('/trades/{position}', [TradesController::class, 'update'])->name('trades.update');
    Route::delete('/trades/{position}', [TradesController::class, 'destroy'])->name('trades.destroy');
    Route::post('/trades/bulk-delete', [TradesController::class, 'bulkDestroy'])->name('trades.bulk-delete');
    
    Route::get('/diary', [DiaryController::class, 'index'])->name('diary');
    Route::post('/diary', [DiaryController::class, 'store'])->name('diary.store');
    // These specific routes must come before the {entry} wildcard
    Route::get('/diary/check-date', [DiaryController::class, 'checkDate'])->name('diary.check-date');
    Route::post('/diary/upload-image', [DiaryController::class, 'uploadImage'])->name('diary.upload-image');
    Route::get('/diary/{entry}', [DiaryController::class, 'show'])->name('diary.show');
    Route::patch('/diary/{entry}', [DiaryController::class, 'update'])->name('diary.update');
    Route::delete('/diary/{entry}', [DiaryController::class, 'destroy'])->name('diary.destroy');
    
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
    Route::patch('/settings/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::patch('/settings/password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::post('/settings/tags', [App\Http\Controllers\SettingsController::class, 'storeTag'])->name('settings.tags.store');
    Route::patch('/settings/tags/{tag}', [App\Http\Controllers\SettingsController::class, 'updateTag'])->name('settings.tags.update');
    Route::delete('/settings/tags/{tag}', [App\Http\Controllers\SettingsController::class, 'destroyTag'])->name('settings.tags.destroy');
    Route::post('/settings/balances', [App\Http\Controllers\SettingsController::class, 'storeBalance'])->name('settings.balances.store');
    Route::delete('/settings/balances/{balance}', [App\Http\Controllers\SettingsController::class, 'destroyBalance'])->name('settings.balances.destroy');
    
    Route::post('/trades/{position}/tags/{tag}/attach', [TradesController::class, 'attachTag'])->name('trades.tags.attach');
    Route::delete('/trades/{position}/tags/{tag}/detach', [TradesController::class, 'detachTag'])->name('trades.tags.detach');
    Route::post('/trades/{position}/screenshots', [TradesController::class, 'storeScreenshot'])->name('trades.screenshots.store');
    Route::delete('/trades/{position}/screenshots/{screenshot}', [TradesController::class, 'destroyScreenshot'])->name('trades.screenshots.destroy');
});

