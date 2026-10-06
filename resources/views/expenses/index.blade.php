@extends('layouts.app')
@section('title', 'Expenses')
@section('content')
<div class="page-head"><h4>🧾 Expenses</h4><p>Total ₹{{ number_format($total, 0) }} • category-wise, linked to works.</p></div>
<div class="d-flex justify-content-end mb-3"><a href="{{ route('expenses.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Expense</a></div>
<div class="card"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>What</th><th>Date</th><th class="text-end">Amount</th><th></th></tr></thead><tbody>
@forelse($expenses as $e)<tr>
<td><span class="chip" style="background:{{ '#'.substr(md5($e->category->name), 0, 6) }}22;color:{{ '#'.substr(md5($e->category->name), 0, 6) }}"><i class="bi bi-tag-fill me-1"></i>{{ $e->category->name }}</span>
@if($e->work)<span class="chip chip-work"><i class="bi bi-hammer me-1"></i>{{ $e->work->title }}</span>@endif
<br><small class="text-muted">@if($e->paid_to)<i class="bi bi-person me-1"></i>{{ $e->paid_to }}@endif{{ $e->note ? ' • '.$e->note : '' }}</small></td>
<td><span class="badge text-bg-light border"><i class="bi bi-calendar3 me-1"></i>{{ $e->date->format('d M Y') }}</span></td>
<td class="text-end text-danger fw-bold text-nowrap">− ₹{{ number_format($e->amount, 0) }}</td>
<td class="text-end text-nowrap"><a href="{{ route('expenses.edit', $e) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
<form method="POST" action="{{ route('expenses.destroy', $e) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
@empty<tr><td colspan="4" class="text-muted p-3">No expenses yet.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-2">{{ $expenses->links() }}</div>
@endsection
