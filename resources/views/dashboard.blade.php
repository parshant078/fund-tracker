@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-head"><h4>📊 Project Dashboard</h4><p>Money in, money out and work progress — live.</p></div>
@include('partials.summary')
<div class="row g-3">
  <div class="col-md-6"><div class="card"><div class="card-header fw-bold bg-white d-flex justify-content-between">Recent Funds <a href="{{ route('funds.index') }}" class="small">view all</a></div>
    <table class="table table-sm mb-0"><tbody>
    @forelse($funds as $f)<tr><td>{{ $f->donor_name }}<br><small class="text-muted">{{ $f->date->format('d M Y') }}</small></td><td class="text-end">₹{{ number_format($f->amount, 0) }}</td></tr>
    @empty<tr><td class="text-muted">No funds yet.</td></tr>@endforelse
    </tbody></table></div></div>
  <div class="col-md-6"><div class="card"><div class="card-header fw-bold bg-white d-flex justify-content-between">Recent Expenses <a href="{{ route('expenses.index') }}" class="small">view all</a></div>
    <table class="table table-sm mb-0"><tbody>
    @forelse($expenses as $e)<tr><td>{{ $e->category->name }}@if($e->work) • {{ $e->work->title }}@endif<br><small class="text-muted">{{ $e->date->format('d M Y') }}{{ $e->paid_to ? ' • '.$e->paid_to : '' }}</small></td><td class="text-end">₹{{ number_format($e->amount, 0) }}</td></tr>
    @empty<tr><td class="text-muted">No expenses yet.</td></tr>@endforelse
    </tbody></table></div></div>
</div>
@endsection
