@extends('layouts.app')
@section('title', 'Funds')
@section('content')
<div class="page-head"><h4>💰 Funds Received</h4><p>Total ₹{{ number_format($total, 0) }} • every rupee tracked by donor.</p></div>
<div class="d-flex justify-content-end mb-3"><a href="{{ route('funds.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Fund</a></div>
<div class="card"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Donor</th><th>Date</th><th class="text-end">Amount</th><th></th></tr></thead><tbody>
@forelse($funds as $f)<tr>
<td><span class="avatar">{{ strtoupper(mb_substr($f->donor_name, 0, 1)) }}</span> <b>{{ $f->donor_name }}</b>@if($f->note)<br><small class="text-muted ms-5">{{ $f->note }}</small>@endif</td>
<td><span class="badge text-bg-light border"><i class="bi bi-calendar3 me-1"></i>{{ $f->date->format('d M Y') }}</span></td>
<td class="text-end text-success fw-bold text-nowrap">+ ₹{{ number_format($f->amount, 0) }}</td>
<td class="text-end text-nowrap"><a href="{{ route('funds.edit', $f) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
<form method="POST" action="{{ route('funds.destroy', $f) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
@empty<tr><td colspan="4" class="text-muted p-3">No funds yet.</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-2">{{ $funds->links() }}</div>
@endsection
