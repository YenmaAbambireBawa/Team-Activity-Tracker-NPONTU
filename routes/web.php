<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// redirect root to login or to the daily view
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('logs.daily')
        : redirect()->route('login');
});

// Laravel Breeze / Fortify handles auth routes (login, logout)
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login'); Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']); Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout'); // registration disabled — admin creates accounts

Route::middleware('auth')->group(function () {

    // daily activity tracking view — main screen for all team members
    Route::get('/daily', [ActivityLogController::class, 'dailyView'])->name('logs.daily');
    Route::get('/activities/{activity}/update', [ActivityLogController::class, 'updateForm'])->name('logs.update.form');
    Route::post('/logs', [ActivityLogController::class, 'store'])->name('logs.store');
    Route::get('/activities/{activity}/history', [ActivityLogController::class, 'history'])->name('logs.history');

    // reporting
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // activity management — admin only (enforced inside controller)
    Route::resource('activities', ActivityController::class)->except(['show']);

    // user management — admin only
    Route::resource('users', UserController::class)->except(['show', 'destroy']);
});
