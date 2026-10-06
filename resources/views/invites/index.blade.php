@extends('layouts.app')
@section('title', 'Funder Invites')
@section('content')
<div class="page-head"><h4>✉️ Funder Invites</h4><p>Share a code — the funder sees everything live, view only.</p></div>
<div class="card mb-3"><div class="card-body"><form method="POST" action="{{ route('invites.store') }}">
@csrf<div class="row g-2"><div class="col-md-5"><input name="label" class="form-control" placeholder="Funder display name" required></div>
<div class="col-md-5"><input name="donor_name" class="form-control" placeholder="Donor name in Funds (for 'You gave' total)"></div>
<div class="col-md-2"><button class="btn btn-dark w-100">Create</button></div></div>
<p class="text-muted small mt-2 mb-0">Tip: put the exact donor name used in Funds so the funder sees their personal "You gave ₹X" total.</p>
</form></div></div>
<div class="card"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Name</th><th>Code</th><th>Link</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($invites as $i)<tr><td>{{ $i->label }}@if($i->donor_name)<br><small class="text-muted">donor: {{ $i->donor_name }}</small>@endif</td><td><code class="fs-6">{{ $i->code }}</code></td><td><a href="{{ route('funder.show', $i->code) }}">/f/{{ $i->code }}</a></td>
<td>{!! $i->active ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-secondary">Off</span>' !!}</td>
<td class="text-end text-nowrap"><form method="POST" action="{{ route('invites.toggle', $i) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-{{ $i->active ? 'warning' : 'success' }}">{{ $i->active ? 'Disable' : 'Enable' }}</button></form>
<form method="POST" action="{{ route('invites.destroy', $i) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
@empty<tr><td colspan="5" class="text-muted">No invites yet.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
