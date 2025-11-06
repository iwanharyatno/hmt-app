<?php

use App\Http\Controllers\Admin\HmtController;
use App\Http\Controllers\Admin\LearningStyleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TrixAttachmentController;
use App\Http\Middleware\EnsureUserIsAdmin;

Route::get('/', function () {
    return redirect()->route('user.dashboard');
})->name('home');

Route::prefix('user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    // Route::get('/contact', [UserController::class, 'contact'])->name('user.contact');
    Route::prefix('quiz')->middleware('auth')->group(function () {
        Route::get('/learning-style', [QuizController::class, 'learningStyle'])->name('user.quiz.learning-style');
        Route::post('/learning-style/submit', [LearningStyleController::class, 'submit'])
            ->name('user.learning-style.submit');
        Route::get('/hmt', [QuizController::class, 'hmt'])->name('user.quiz.hmt');

        Route::post('/hmt/start', [HmtController::class, 'startSession'])->name('quiz.hmt.start');
        Route::post('/hmt/answer', [HmtController::class, 'submitAnswer'])->name('quiz.hmt.answer');
        Route::post('/hmt/finish', [HmtController::class, 'finishSession'])->name('quiz.hmt.finish');
    });
    Route::get('/result', [UserController::class, 'result'])->name('user.result');
});

// Admin
Route::prefix('admin')->middleware('auth', EnsureUserIsAdmin::class)->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('learning-style', [LearningStyleController::class, 'index'])->name('admin.learning-style.index');
    Route::get('learning-style/editor', [LearningStyleController::class, 'create'])->name('admin.learning-style.editor');
    Route::get('learning-style/all', [LearningStyleController::class, 'all'])->name('admin.learning-style.all');
    Route::post('learning-style/save', [LearningStyleController::class, 'save'])->name('admin.learning-style.save');
    Route::delete('learning-style/delete/{id}', [LearningStyleController::class, 'destroy'])->name('admin.learning-style.destroy');
    Route::get('learning-style/history', [LearningStyleController::class, 'history'])->name('admin.learning-style.history');
    Route::get('learning-style/export', [LearningStyleController::class, 'export'])->name('admin.learning-style.export');

    Route::resource('hmt', HmtController::class)->except(['show'])->names('admin.hmt');

    Route::get('hmt/histories', [HmtController::class, 'history'])->name('admin.hmt.history');
    Route::get('hmt/histories/show/{id}', [HmtController::class, 'showAnswer'])->name('admin.hmt.histories.show');
    Route::get('hmt/histories/export', [HmtController::class, 'exportHistoriesCsv'])
        ->name('admin.hmt.histories.export');
    Route::get('hmt/histories/export/{userId}', [HmtController::class, 'exportHistoriesSingleCsv'])
        ->name('admin.hmt.histories.single-export');

    Route::get('settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('admin.settings.update');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'handleLogin'])->name('handle-login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'handleRegister'])->name('handle-register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/trix-attachments', [TrixAttachmentController::class, 'store'])->name('trix.attachments.store');