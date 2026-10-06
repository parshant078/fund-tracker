<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Fund;
use App\Models\Invite;
use Illuminate\Http\Request;

class FunderController extends Controller
{
    public function codeForm()
    {
        return view('funder.code');
    }

    public function checkCode(Request $request)
    {
        $data = $request->validate(['code' => 'required|string|max:20']);
        $invite = Invite::where('code', strtoupper(trim($data['code'])))->where('active', true)->first();

        if (! $invite) {
            return back()->with('error', 'Invalid or disabled code.');
        }

        return redirect()->route('funder.show', $invite->code);
    }

    public function show(string $code)
    {
        $invite = Invite::where('code', strtoupper($code))->where('active', true)->firstOrFail();

        $personalTotal = $invite->donor_name
            ? (float) Fund::where('donor_name', $invite->donor_name)->sum('amount')
            : 0;

        $funds = Fund::orderByDesc('date')->get();
        $expenses = Expense::with(['category', 'work'])->orderByDesc('date')->get();

        return view('funder.show', array_merge(
            DashboardController::summary(),
            compact('invite', 'funds', 'expenses', 'personalTotal')
        ));
    }
}
