<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

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
        $data = $request->validate(['amount' => 'required|numeric|min:1']);
        $work->increment('advance_paid', $data['amount']);

        return back()->with('ok', 'Advance updated.');
    }
}
