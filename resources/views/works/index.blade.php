@extends('layouts.app')
@section('title', 'Works')
@section('content')
<div class="page-head"><h4>🏗️ Works & Plan</h4><p>Agreed amounts, advances, status and what's next.</p></div>
<div class="d-flex justify-content-end mb-3"><a href="{{ route('works.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Work</a></div>
@forelse($works as $w)
@php $spent = (float)($w->expenses_sum_amount ?? 0); $pct = $w->budget > 0 ? min(100, round($spent / $w->budget * 100)) : 0; $remaining = max(0, $w->budget - $spent); @endphp
<div class="card mb-3"><div class="card-body">
<div class="d-flex justify-content-between align-items-start gap-2">
<h5 class="mb-1"><i class="bi bi-hammer text-muted me-1"></i>{{ $w->title }}</h5>
<div class="d-flex flex-column align-items-end gap-1">
@if($w->status == 'completed')<span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i>Completed</span>
@elseif($w->status == 'in_progress')<span class="badge text-bg-warning"><i class="bi bi-arrow-repeat me-1"></i>In Progress</span>
@else<span class="badge text-bg-info"><i class="bi bi-calendar-event me-1"></i>Planned</span>@endif
@if($remaining > 0)<span class="fw-bold text-danger"><i class="bi bi-exclamation-circle me-1"></i>Pending ₹{{ number_format($remaining, 0) }}</span>
@else<span class="badge text-bg-success"><i class="bi bi-check-all me-1"></i>Fully Paid</span>@endif
</div>
</div>
<div class="d-flex gap-3 flex-wrap small mt-2 mb-2">
<span><i class="bi bi-wallet me-1 text-muted"></i>Budget <b>₹{{ number_format($w->budget, 0) }}</b></span>
<span><i class="bi bi-receipt me-1 text-muted"></i>Spent <b class="text-danger">₹{{ number_format($spent, 0) }}</b></span>
<span><i class="bi bi-cash-coin me-1 text-muted"></i>Advance <b class="text-primary">₹{{ number_format($w->advance_paid, 0) }}</b></span>
<span><i class="bi bi-piggy-bank me-1 text-muted"></i>Remaining <b class="{{ $remaining > 0 ? 'text-danger bg-warning-subtle px-2 py-1 rounded' : 'text-success' }}">₹{{ number_format($remaining, 0) }}</b></span>
</div>
<div class="progress mb-2" style="height:8px"><div class="progress-bar {{ $pct >= 100 ? 'bg-danger' : ($pct >= 70 ? 'bg-warning' : 'bg-success') }}" style="width:{{ $pct }}%"></div></div>
@if($w->notes)<p class="text-muted small mb-2"><i class="bi bi-sticky me-1"></i>{{ $w->notes }}</p>@endif
<div class="d-flex gap-2 flex-wrap align-items-center">
<a href="{{ route('works.edit', $w) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil me-1"></i>Edit / Status</a>
<form method="POST" action="{{ route('works.advance', $w) }}" class="d-flex gap-1">@csrf<input name="amount" type="number" min="1" step="0.01" class="form-control form-control-sm" style="width:130px" placeholder="+ Advance ₹" required><button class="btn btn-sm btn-outline-primary">Add</button></form>
<form method="POST" action="{{ route('works.destroy', $w) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
</div></div></div>
@empty<div class="card"><div class="card-body text-center text-muted py-4">No works yet.<br>Example: <b>Gypsum Work — ₹70,000</b></div></div>@endforelse
@endsection
