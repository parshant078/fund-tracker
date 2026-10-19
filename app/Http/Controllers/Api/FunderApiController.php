<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\Expense;
use App\Models\Fund;
use App\Models\Invite;
use Illuminate\Http\JsonResponse;

class FunderApiController extends Controller
{
    public function show(string $code): JsonResponse
    {
        $invite = Invite::where('code', strtoupper(trim($code)))->where('active', true)->first();

        if (! $invite) {
            return response()->json(['message' => 'Invalid or disabled code.'], 404);
        }

        $personalTotal = $invite->donor_name
            ? (float) Fund::where('donor_name', $invite->donor_name)->sum('amount')
            : 0;

        $summary = DashboardController::summary();

        return response()->json([
            'invite' => [
                'label' => $invite->label,
                'code' => $invite->code,
                'donor_name' => $invite->donor_name,
            ],
            'personal_total' => $personalTotal,
            'total_funds' => $summary['totalFunds'],
            'total_expenses' => $summary['totalExpenses'],
            'balance' => $summary['totalFunds'] - $summary['totalExpenses'],
            'by_category' => $summary['byCategory'],
            'works' => $summary['works'],
            'trend' => $summary['trend'],
            'funds' => Fund::orderByDesc('date')->get(),
            'expenses' => Expense::with(['category', 'work'])->orderByDesc('date')->get(),
        ]);
    }
}
