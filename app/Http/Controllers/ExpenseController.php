<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Work;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['category', 'work'])->orderByDesc('date')->paginate(20);
        $total = (float) Expense::sum('amount');

        return view('expenses.index', compact('expenses', 'total'));
    }

    public function create()
    {
        return view('expenses.form', [
            'expense' => new Expense(['date' => now()->toDateString()]),
            'categories' => Category::orderBy('name')->get(),
            'works' => Work::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'work_id' => 'nullable|exists:works,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'paid_to' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:255',
        ]);
        Expense::create($data);

        return redirect()->route('expenses.index')->with('ok', 'Expense added.');
    }

    public function edit(Expense $expense)
    {
        return view('expenses.form', [
            'expense' => $expense,
            'categories' => Category::orderBy('name')->get(),
            'works' => Work::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Expense $expense)
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

        return redirect()->route('expenses.index')->with('ok', 'Expense updated.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('ok', 'Expense deleted.');
    }
}
