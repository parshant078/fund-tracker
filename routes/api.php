<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\FundController;
use App\Http\Controllers\Api\FunderApiController;
use App\Http\Controllers\Api\InviteController;
use App\Http\Controllers\Api\SummaryController;
use App\Http\Controllers\Api\WorkController;
use Illuminate\Support\Facades\Route;

// Public: shared funder view via invite code (revocable in Invites).
Route::get('/funder/{code}', [FunderApiController::class, 'show']);

// Public: connectivity check for the mobile app (no auth, no secrets).
Route::get('/ping', fn () => response()->json([
    'ok' => true,
    'app' => 'fund-tracker',
    'time' => now()->toIso8601String(),
]));

Route::middleware('api.token')->group(function () {
    Route::get('/summary', [SummaryController::class, 'show']);

    Route::apiResource('funds', FundController::class);
    Route::apiResource('expenses', ExpenseController::class);
    Route::apiResource('categories', CategoryController::class)->except(['show', 'update']);
    Route::apiResource('invites', InviteController::class)->except(['show', 'update']);
    Route::post('invites/{invite}/toggle', [InviteController::class, 'toggle']);

    Route::apiResource('works', WorkController::class);
    Route::post('works/{work}/advance', [WorkController::class, 'advance']);
});
