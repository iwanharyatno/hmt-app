<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.dashboard'); // arahkan ke views/user/dashboard.blade.php
});

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/contact', [UserController::class, 'contact'])->name('contact');
    Route::get('/quiz/learning-style', [UserController::class, 'learningStyle'])->name('quiz.learning-style');
    Route::get('/quiz/hmt', [UserController::class, 'hmt'])->name('quiz.hmt');
    Route::get('/result', [UserController::class, 'result'])->name('result');
});
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [UserController::class, 'login'])->name('login');

});

use App\Http\Controllers\AdminController;

// Admin
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/learning-style', [AdminController::class, 'learningIndex'])->name('admin.learning-style.index');
    Route::get('/learning-style/create', [AdminController::class, 'learningCreate'])->name('admin.learning-style.create');
    Route::get('/hmt', [AdminController::class, 'hmtIndex'])->name('admin.hmt.index');
    Route::get('/hmt/create', [AdminController::class, 'hmtCreate'])->name('admin.hmt.create');
});
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');