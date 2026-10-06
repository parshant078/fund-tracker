@extends('layouts.app')
@section('title', 'Admin Login')
@section('content')
<div class="row justify-content-center"><div class="col-md-5"><div class="card auth-card"><div class="card-body p-4">
<div class="text-center mb-3" style="font-size:2.6rem">💰</div>
<h4 class="mb-1 text-center">Welcome back</h4><p class="text-muted small text-center">Log in to manage funds, expenses and works.</p>
<form method="POST" action="{{ route('login.post') }}">@csrf
<div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="{{ old('email') }}"></div>
<div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
<button class="btn btn-dark w-100">Login</button>
</form>
<hr><p class="text-muted small mb-0">Are you a funder? <a href="{{ route('funder.code') }}">Open your view with an invite code</a></p>
</div></div></div></div>
@endsection
