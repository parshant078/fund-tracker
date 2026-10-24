<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkController extends Controller
{
    public function index(): JsonResponse
    {
        $works = Work::withSum('expenses', 'amount')->orderBy('sort_order')->orderBy('id')->get();

        return response()->json(['data' => $works]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:150',
            'budget' => 'required|numeric|min:0',
            'advance_paid' => 'nullable|numeric|min:0',
            'status' => 'required|in:planned,in_progress,completed',
            'notes' => 'nullable|string',
        ]);
        $data['advance_paid'] ??= 0;
        $data['sort_order'] = (int) Work::max('sort_order') + 1;

        return response()->json(['data' => Work::create($data)], 201);
    }

    public function show(Work $work): JsonResponse
    {
        return response()->json(['data' => $work->loadSum('expenses', 'amount')]);
    }

    public function update(Request $request, Work $work): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:150',
            'budget' => 'required|numeric|min:0',
            'advance_paid' => 'nullable|numeric|min:0',
            'status' => 'required|in:planned,in_progress,completed',
            'notes' => 'nullable|string',
        ]);
        $data['advance_paid'] ??= 0;
        $work->update($data);

        return response()->json(['data' => $work->fresh()]);
    }

    public function destroy(Work $work): JsonResponse
    {
        $work->delete();

        return response()->json(['message' => 'Work deleted.']);
    }

    public function advance(Request $request, Work $work): JsonResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'category_id' => 'nullable|exists:categories,id',
            'date' => 'nullable|date',
            'paid_to' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:255',
        ]);

        $categoryId = $data['category_id'] ?? Category::where('name', 'Material')->value('id')
            ?? Category::orderBy('id')->value('id');

        abort_if(! $categoryId, 422, 'No category found for advance expense.');

        $expense = DB::transaction(function () use ($data, $work, $categoryId) {
            $work->increment('advance_paid', $data['amount']);

            return Expense::create([
                'category_id' => $categoryId,
                'work_id' => $work->id,
                'amount' => $data['amount'],
                'date' => $data['date'] ?? now()->toDateString(),
                'paid_to' => $data['paid_to'] ?? null,
                'note' => $data['note'] ?? ('Advance for '.$work->title),
            ]);
        });

        return response()->json(['data' => $work->fresh(), 'expense' => $expense->load(['category', 'work'])]);
    }
}
