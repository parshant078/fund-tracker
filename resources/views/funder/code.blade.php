@extends('layouts.app')
@section('title', 'Funder Access')
@section('content')
<div class="row justify-content-center"><div class="col-md-5"><div class="card"><div class="card-body p-4 text-center">
<div style="font-size:2.5rem">🏗️</div>
<h4 class="mb-1">Project Live Status</h4>
<p class="text-muted small">Enter the invite code shared by the admin.</p>
<form method="POST" action="{{ route('funder.check') }}">@csrf
<div class="mb-3"><input name="code" class="form-control form-control-lg text-center text-uppercase" placeholder="CODE" required style="letter-spacing:4px"></div>
<button class="btn btn-dark w-100">View Project</button>
</form></div></div></div></div>
@endsection
