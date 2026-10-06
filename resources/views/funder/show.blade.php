@extends('layouts.app')
@section('title', 'Project Status')
@section('content')
<div class="card mb-3" style="background:linear-gradient(120deg,#0f172a,#4c1d95);border:0"><div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2 text-white">
<div><h4 class="mb-0">Hello, {{ $invite->label }} 🙏</h4><small class="text-white-50">Live project accounts • view only</small></div>
<span class="badge text-bg-light">Invite {{ $invite->code }}</span>
</div></div>
@if($invite->donor_name)
<div class="row g-3 mb-3">
  <div class="col-6"><div class="stat stat-gold"><small>You gave ({{ $invite->donor_name }})</small><h3>₹{{ number_format($personalTotal, 0) }}</h3></div></div>
  <div class="col-6"><div class="stat stat-blue"><small>Balance in hand (project)</small><h3>₹{{ number_format($totalFunds - $totalExpenses, 0) }}</h3></div></div>
</div>
@endif
@include('partials.summary')
<div class="row g-3">
<div class="col-md-6"><div class="card"><div class="card-header fw-bold bg-white">Who Gave What</div><div class="table-responsive"><table class="table table-sm mb-0"><tbody>
@forelse($funds as $f)<tr><td>{{ $f->donor_name }}@if($invite->donor_name && $f->donor_name === $invite->donor_name)<span class="badge text-bg-warning ms-1">you</span>@endif<br><small class="text-muted">{{ $f->date->format('d M Y') }}{{ $f->note ? ' • '.$f->note : '' }}</small></td><td class="text-end">₹{{ number_format($f->amount, 0) }}</td></tr>
@empty<tr><td class="text-muted">No funds yet.</td></tr>@endforelse
</tbody></table></div></div></div>
<div class="col-md-6"><div class="card"><div class="card-header fw-bold bg-white">Expense Details</div><div class="table-responsive"><table class="table table-sm mb-0"><tbody>
@forelse($expenses as $e)<tr><td>{{ $e->category->name }}@if($e->work) • {{ $e->work->title }}@endif<br><small class="text-muted">{{ $e->date->format('d M Y') }}{{ $e->paid_to ? ' • '.$e->paid_to : '' }}{{ $e->note ? ' • '.$e->note : '' }}</small></td><td class="text-end">₹{{ number_format($e->amount, 0) }}</td></tr>
@empty<tr><td class="text-muted">No expenses yet.</td></tr>@endforelse
</tbody></table></div></div></div>
</div>
@endsection
