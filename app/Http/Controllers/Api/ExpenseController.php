<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(): JsonResponse
    {
        $expenses = Expense::with(['category', 'work'])->orderByDesc('date')->paginate(20);

        return response()->json([
            'data' => $expenses->items(),
            'meta' => [
                'current_page' => $expenses->currentPage(),
                'last_page' => $expenses->lastPage(),
                'per_page' => $expenses->perPage(),
                'total' => $expenses->total(),
                'total_amount' => (float) Expense::sum('amount'),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'work_id' => 'nullable|exists:works,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'paid_to' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:255',
        ]);

        return response()->json(['data' => Expense::create($data)->load(['category', 'work'])], 201);
    }

    public function show(Expense $expense): JsonResponse
    {
        return response()->json(['data' => $expense->load(['category', 'work'])]);
    }

    public function update(Request $request, Expense $expense): JsonResponse
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'work_id' => 'nullable|exists:works,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'paid_to' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:255',
        ]);
        $expense->update($data);

        return response()->json(['data' => $expense->fresh()->load(['category', 'work'])]);
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $expense->delete();

        return response()->json(['message' => 'Expense deleted.']);
    }
}
