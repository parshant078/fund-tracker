<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkController extends Controller
{
    public function index()
    {
        $works = Work::withSum('expenses', 'amount')->orderBy('sort_order')->orderBy('id')->get();

        return view('works.index', compact('works'));
    }

    public function create()
    {
        return view('works.form', ['work' => new Work(['status' => 'planned'])]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:150',
            'budget' => 'required|numeric|min:0',
            'advance_paid' => 'nullable|numeric|min:0',
            'status' => 'required|in:planned,in_progress,completed',
            'notes' => 'nullable|string',
        ]);
        $data['advance_paid'] ??= 0;
        $max = (int) Work::max('sort_order');
        $data['sort_order'] = $max + 1;
        Work::create($data);

        return redirect()->route('works.index')->with('ok', 'Work added.');
    }

    public function edit(Work $work)
    {
        return view('works.form', compact('work'));
    }

    public function update(Request $request, Work $work)
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

        return redirect()->route('works.index')->with('ok', 'Work updated.');
    }

    public function destroy(Work $work)
    {
        $work->delete();

        return redirect()->route('works.index')->with('ok', 'Work deleted.');
    }

    public function advance(Request $request, Work $work)
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

        DB::transaction(function () use ($data, $work, $categoryId) {
            $work->increment('advance_paid', $data['amount']);

            Expense::create([
                'category_id' => $categoryId,
                'work_id' => $work->id,
                'amount' => $data['amount'],
                'date' => $data['date'] ?? now()->toDateString(),
                'paid_to' => $data['paid_to'] ?? null,
                'note' => $data['note'] ?? ('Advance for '.$work->title),
            ]);
        });

        return back()->with('ok', 'Advance added + expense entry created.');
    }
}
