<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\FunderController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/funder', [FunderController::class, 'codeForm'])->name('funder.code');
Route::post('/funder', [FunderController::class, 'checkCode'])->name('funder.check');
Route::get('/f/{code}', [FunderController::class, 'show'])->name('funder.show');

Route::middleware('admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('funds', FundController::class)->except('show');
    Route::resource('expenses', ExpenseController::class)->except('show');
    Route::resource('works', WorkController::class)->except('show');
    Route::post('works/{work}/advance', [WorkController::class, 'advance'])->name('works.advance');

    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('invites', [InviteController::class, 'index'])->name('invites.index');
    Route::post('invites', [InviteController::class, 'store'])->name('invites.store');
    Route::post('invites/{invite}/toggle', [InviteController::class, 'toggle'])->name('invites.toggle');
    Route::delete('invites/{invite}', [InviteController::class, 'destroy'])->name('invites.destroy');
});
