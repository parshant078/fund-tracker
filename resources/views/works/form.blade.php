@extends('layouts.app')
@section('title', $work->exists ? 'Edit Work' : 'Add Work')
@section('content')
<div class="row justify-content-center"><div class="col-md-6"><div class="card"><div class="card-body">
<h4>{{ $work->exists ? 'Edit Work' : 'Add Work' }}</h4>
<form method="POST" action="{{ $work->exists ? route('works.update', $work) : route('works.store') }}">@csrf @if($work->exists) @method('PUT') @endif
<div class="mb-3"><label class="form-label">Work name *</label><input name="title" class="form-control" required value="{{ old('title', $work->title) }}" placeholder="e.g. Gypsum Work"></div>
<div class="mb-3"><label class="form-label">Agreed amount / Budget ₹ *</label><input name="budget" type="number" step="0.01" min="0" class="form-control" required value="{{ old('budget', $work->budget) }}"></div>
<div class="mb-3"><label class="form-label">Advance paid ₹</label><input name="advance_paid" type="number" step="0.01" min="0" class="form-control" value="{{ old('advance_paid', $work->advance_paid) }}"></div>
<div class="mb-3"><label class="form-label">Status *</label><select name="status" class="form-select">@foreach(\App\Models\Work::STATUSES as $k => $v)<option value="{{ $k }}" {{ old('status', $work->status) == $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
<div class="mb-3"><label class="form-label">Notes / next plan</label><textarea name="notes" class="form-control" rows="3" placeholder="What is next after this work?">{{ old('notes', $work->notes) }}</textarea></div>
<button class="btn btn-dark">Save</button> <a href="{{ route('works.index') }}" class="btn btn-light">Back</a>
</form></div></div></div></div>
@endsection
