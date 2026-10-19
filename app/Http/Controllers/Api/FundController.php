<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fund;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FundController extends Controller
{
    public function index(): JsonResponse
    {
        $funds = Fund::orderByDesc('date')->paginate(20);

        return response()->json([
            'data' => $funds->items(),
            'meta' => [
                'current_page' => $funds->currentPage(),
                'last_page' => $funds->lastPage(),
                'per_page' => $funds->perPage(),
                'total' => $funds->total(),
                'total_amount' => (float) Fund::sum('amount'),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'donor_name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        return response()->json(['data' => Fund::create($data)], 201);
    }

    public function show(Fund $fund): JsonResponse
    {
        return response()->json(['data' => $fund]);
    }

    public function update(Request $request, Fund $fund): JsonResponse
    {
        $data = $request->validate([
            'donor_name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);
        $fund->update($data);

        return response()->json(['data' => $fund->fresh()]);
    }

    public function destroy(Fund $fund): JsonResponse
    {
        $fund->delete();

        return response()->json(['message' => 'Fund deleted.']);
    }
}
