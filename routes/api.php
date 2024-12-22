<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('forgot-password', [ResetPasswordController::class, 'forgotPassword'])->name('password.request');
Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/articles', [ArticleController::class, 'index'])->name('article.index');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('article.show');
});
