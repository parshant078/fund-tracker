@extends('layouts.app')
@section('title', $expense->exists ? 'Edit Expense' : 'Add Expense')
@section('content')
<div class="row justify-content-center"><div class="col-md-6"><div class="card"><div class="card-body">
<h4>{{ $expense->exists ? 'Edit Expense' : 'Add Expense' }}</h4>
<form method="POST" action="{{ $expense->exists ? route('expenses.update', $expense) : route('expenses.store') }}">@csrf @if($expense->exists) @method('PUT') @endif
<div class="mb-3"><label class="form-label">Category *</label><select name="category_id" class="form-select" required><option value="">-- Select --</option>@foreach($categories as $c)<option value="{{ $c->id }}" {{ old('category_id', $expense->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach</select></div>
<div class="mb-3"><label class="form-label">Linked work (optional)</label><select name="work_id" class="form-select"><option value="">-- General expense --</option>@foreach($works as $w)<option value="{{ $w->id }}" {{ old('work_id', $expense->work_id) == $w->id ? 'selected' : '' }}>{{ $w->title }}</option>@endforeach</select></div>
<div class="mb-3"><label class="form-label">Amount ₹ *</label><input name="amount" type="number" step="0.01" min="1" class="form-control" required value="{{ old('amount', $expense->amount) }}"></div>
<div class="mb-3"><label class="form-label">Date *</label><input name="date" type="date" class="form-control" required value="{{ old('date', $expense->date?->format('Y-m-d')) }}"></div>
<div class="mb-3"><label class="form-label">Paid to</label><input name="paid_to" class="form-control" value="{{ old('paid_to', $expense->paid_to) }}" placeholder="Vendor / worker name"></div>
<div class="mb-3"><label class="form-label">Note</label><input name="note" class="form-control" value="{{ old('note', $expense->note) }}"></div>
<button class="btn btn-dark">Save</button> <a href="{{ route('expenses.index') }}" class="btn btn-light">Back</a>
</form></div></div></div></div>
@endsection
