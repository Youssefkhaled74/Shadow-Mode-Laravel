<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SessionManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LiveCoachingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SessionReportController;
use App\Http\Controllers\SessionRoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/create', [SessionController::class, 'create'])->name('sessions.create');
    Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');
    Route::get('/sessions/join', [SessionController::class, 'joinForm'])->name('sessions.join');
    Route::post('/sessions/join', [SessionController::class, 'join'])->name('sessions.join.submit');
    Route::get('/sessions/{trainingSession}', [SessionController::class, 'show'])->name('sessions.show');
    Route::patch('/sessions/{trainingSession}/state', [SessionController::class, 'updateState'])->name('sessions.state.update');
    Route::post('/sessions/{trainingSession}/leave', [SessionController::class, 'leave'])->name('sessions.leave');

    Route::get('/rooms/{trainingSession}', [SessionRoomController::class, 'show'])->name('rooms.show');
    Route::post('/rooms/{trainingSession}/hints', [LiveCoachingController::class, 'storeHint'])->name('rooms.hints.store');
    Route::post('/rooms/{trainingSession}/metrics', [LiveCoachingController::class, 'storeMetrics'])->name('rooms.metrics.store');

    Route::post('/sessions/{trainingSession}/reports/generate', [SessionReportController::class, 'generate'])->name('reports.generate');
    Route::get('/sessions/{trainingSession}/report', [SessionReportController::class, 'show'])->name('reports.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::get('/sessions', [SessionManagementController::class, 'index'])->name('sessions.index');
        Route::delete('/sessions/{trainingSession}', [SessionManagementController::class, 'destroy'])->name('sessions.destroy');
    });
});

require __DIR__.'/auth.php';
