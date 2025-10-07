<?php

use App\Http\Controllers\Admin\HmtController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\QuizController;
use App\Http\Middleware\EnsureUserIsAdmin;

Route::get('/', function () {
    return view('user.dashboard'); // arahkan ke views/user/dashboard.blade.php
})->name('home');

Route::prefix('user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/contact', [UserController::class, 'contact'])->name('user.contact');
    Route::prefix('quiz')->middleware('auth')->group(function () {
        Route::get('/learning-style', [UserController::class, 'learningStyle'])->name('user.quiz.learning-style');
        Route::get('/hmt', [QuizController::class, 'hmt'])->name('user.quiz.hmt');
        Route::post('/hmt/answer', [QuizController::class, 'saveHmtAnswer'])->name('quiz.hmt.answer');
    });
    Route::get('/result', [UserController::class, 'result'])->name('user.result');
});

// Admin
Route::prefix('admin')->middleware('auth', EnsureUserIsAdmin::class)->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/learning-style', [AdminController::class, 'learningIndex'])->name('admin.learning-style.index');
    Route::get('/learning-style/create', [AdminController::class, 'learningCreate'])->name('admin.learning-style.create');

    Route::resource('hmt', HmtController::class)->except(['show'])->names('admin.hmt');

    Route::get('hmt/histories', [HmtController::class, 'history'])->name('admin.hmt.history');
    Route::get('hmt/histories/export', [HmtController::class, 'exportHistoriesCsv'])
        ->name('admin.hmt.histories.export');
    Route::get('hmt/histories/export/{userId}', [HmtController::class, 'exportHistoriesSingleCsv'])
        ->name('admin.hmt.histories.single-export');
    Route::get('hmt/histories/show/{id}', [HmtController::class, 'showAnswer'])->name('admin.hmt.histories.show');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'handleLogin'])->name('handle-login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'handleRegister'])->name('handle-register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');