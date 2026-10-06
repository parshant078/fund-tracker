@extends('layouts.app')
@section('title', $fund->exists ? 'Edit Fund' : 'Add Fund')
@section('content')
<div class="row justify-content-center"><div class="col-md-6"><div class="card"><div class="card-body">
<h4>{{ $fund->exists ? 'Edit Fund' : 'Add Fund' }}</h4>
<form method="POST" action="{{ $fund->exists ? route('funds.update', $fund) : route('funds.store') }}">@csrf @if($fund->exists) @method('PUT') @endif
<div class="mb-3"><label class="form-label">Donor name *</label><input name="donor_name" class="form-control" required value="{{ old('donor_name', $fund->donor_name) }}" placeholder="Who gave the money?"></div>
<div class="mb-3"><label class="form-label">Amount ₹ *</label><input name="amount" type="number" step="0.01" min="1" class="form-control" required value="{{ old('amount', $fund->amount) }}"></div>
<div class="mb-3"><label class="form-label">Date *</label><input name="date" type="date" class="form-control" required value="{{ old('date', $fund->date?->format('Y-m-d')) }}"></div>
<div class="mb-3"><label class="form-label">Note</label><input name="note" class="form-control" value="{{ old('note', $fund->note) }}"></div>
<button class="btn btn-dark">Save</button> <a href="{{ route('funds.index') }}" class="btn btn-light">Back</a>
</form></div></div></div></div>
@endsection
