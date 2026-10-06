<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Fund;
use App\Models\Work;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public static function summary(): array
    {
        $totalFunds = (float) Fund::sum('amount');
        $totalExpenses = (float) Expense::sum('amount');

        $byCategory = Category::withSum('expenses', 'amount')->get()
            ->map(fn ($c) => ['name' => $c->name, 'total' => (float) $c->expenses_sum_amount])
            ->filter(fn ($c) => $c['total'] > 0)
            ->sortByDesc('total')->values();

        $works = Work::withSum('expenses', 'amount')->orderBy('sort_order')->orderBy('id')->get();

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $months[$key] = now()->subMonths($i)->format('M y');
        }
        $fundByMonth = Fund::select(DB::raw("strftime('%Y-%m', date) as m"), DB::raw('SUM(amount) as t'))
            ->groupBy('m')->pluck('t', 'm');
        $expByMonth = Expense::select(DB::raw("strftime('%Y-%m', date) as m"), DB::raw('SUM(amount) as t'))
            ->groupBy('m')->pluck('t', 'm');

        $trend = [
            'labels' => array_values($months),
            'funds' => array_map(fn ($k) => (float) ($fundByMonth[$k] ?? 0), array_keys($months)),
            'expenses' => array_map(fn ($k) => (float) ($expByMonth[$k] ?? 0), array_keys($months)),
        ];

        return compact('totalFunds', 'totalExpenses', 'byCategory', 'works', 'trend');
    }

    public function index()
    {
        $funds = Fund::orderByDesc('date')->limit(5)->get();
        $expenses = Expense::with(['category', 'work'])->orderByDesc('date')->limit(8)->get();

        return view('dashboard', array_merge(self::summary(), compact('funds', 'expenses')));
    }
}
