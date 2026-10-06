@extends('layouts.app')
@section('title', 'Categories')
@section('content')
<div class="page-head"><h4>🏷️ Expense Categories</h4><p>Group every expense — charts build themselves.</p></div>
<div class="card mb-3"><div class="card-body"><form method="POST" action="{{ route('categories.store') }}" class="d-flex gap-2">@csrf<input name="name" class="form-control" placeholder="New category (e.g. Material, Labour)" required><button class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i>Add</button></form></div></div>
<div class="row g-3">
@forelse($categories as $c)
<div class="col-6 col-md-4 col-lg-3"><div class="card h-100"><div class="card-body">
<div class="d-flex align-items-center gap-2 mb-1"><span class="dot" style="background:{{ '#'.substr(md5($c->name), 0, 6) }}"></span><b>{{ $c->name }}</b></div>
<div class="fs-5 fw-bold">₹{{ number_format($c->expenses_sum_amount ?? 0, 0) }}</div>
<small class="text-muted">{{ $c->expenses_count ?? $c->expenses()->count() }} entries</small>
<div class="mt-2"><form method="POST" action="{{ route('categories.destroy', $c) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Delete</button></form></div>
</div></div></div>
@empty<div class="col"><p class="text-muted">No categories.</p></div>@endforelse
</div>
@endsection
