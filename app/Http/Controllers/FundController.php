<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use Illuminate\Http\Request;

class FundController extends Controller
{
    public function index()
    {
        $funds = Fund::orderByDesc('date')->paginate(20);
        $total = (float) Fund::sum('amount');

        return view('funds.index', compact('funds', 'total'));
    }

    public function create()
    {
        return view('funds.form', ['fund' => new Fund(['date' => now()->toDateString()])]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'donor_name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);
        Fund::create($data);

        return redirect()->route('funds.index')->with('ok', 'Fund added.');
    }

    public function edit(Fund $fund)
    {
        return view('funds.form', compact('fund'));
    }

    public function update(Request $request, Fund $fund)
    {
        $data = $request->validate([
            'donor_name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);
        $fund->update($data);

        return redirect()->route('funds.index')->with('ok', 'Fund updated.');
    }

    public function destroy(Fund $fund)
    {
        $fund->delete();

        return redirect()->route('funds.index')->with('ok', 'Fund deleted.');
    }
}
