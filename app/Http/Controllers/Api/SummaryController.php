<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\Expense;
use App\Models\Fund;
use Illuminate\Http\JsonResponse;

class SummaryController extends Controller
{
    public function show(): JsonResponse
    {
        $summary = DashboardController::summary();

        return response()->json([
            'total_funds' => $summary['totalFunds'],
            'total_expenses' => $summary['totalExpenses'],
            'balance' => $summary['totalFunds'] - $summary['totalExpenses'],
            'by_category' => $summary['byCategory'],
            'works' => $summary['works'],
            'trend' => $summary['trend'],
            'latest_funds' => Fund::orderByDesc('date')->limit(5)->get(),
            'latest_expenses' => Expense::with(['category', 'work'])->orderByDesc('date')->limit(8)->get(),
        ]);
    }
}
