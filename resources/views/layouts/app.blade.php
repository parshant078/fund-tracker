<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Fund Tracker')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{--ink:#0f172a;--muted:#64748b;--line:#e7ebf3;--bg:#f1f4f9;--indigo:#4f46e5;--violet:#7c3aed;--emerald:#059669;--rose:#e11d48;--amber:#d97706}
*{font-family:'Inter',system-ui,sans-serif}
body{background:var(--bg);color:var(--ink)}
.topbar{background:linear-gradient(120deg,#1e1b4b 0%,#4c1d95 55%,#7c3aed 100%);color:#fff;box-shadow:0 4px 20px rgba(30,27,75,.35)}
.hero-band{background:linear-gradient(120deg,#1e1b4b 0%,#4c1d95 55%,#7c3aed 100%);border-radius:22px;color:#fff;box-shadow:0 10px 30px rgba(76,29,149,.35);overflow:hidden;position:relative}
.hero-band::after{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.08)}
.hero-band::before{content:'';position:absolute;right:60px;bottom:-90px;width:170px;height:170px;border-radius:50%;background:rgba(255,255,255,.06)}
.hero-num{font-size:2rem;font-weight:800;margin:0}
.hero-label{font-size:.7rem;text-transform:uppercase;letter-spacing:1px;opacity:.75;font-weight:600}
.hero-div{border-left:1px solid rgba(255,255,255,.2)}
.stepper{display:flex;align-items:center;gap:0;margin:.6rem 0}
.step{flex:1;text-align:center;position:relative;font-size:.7rem;font-weight:700;color:var(--muted)}
.step .sdot{display:block;width:26px;height:26px;line-height:26px;border-radius:50%;background:#e8ecf3;color:#94a3b8;margin:0 auto 4px;font-size:.75rem}
.step::before{content:'';position:absolute;top:13px;left:-50%;width:100%;height:2px;background:#e8ecf3;z-index:0}
.step:first-child::before{display:none}
.step .sdot{position:relative;z-index:1}
.step.done{color:var(--emerald)}.step.done .sdot{background:var(--emerald);color:#fff}.step.done::before{background:var(--emerald)}
.step.now{color:var(--amber)}.step.now .sdot{background:var(--amber);color:#fff;box-shadow:0 0 0 5px rgba(217,119,6,.15)}
.work-accent{border-left:6px solid var(--muted)}
.work-planned{border-left-color:var(--indigo)}
.work-in_progress{border-left-color:var(--amber)}
.work-completed{border-left-color:var(--emerald)}
.brand{font-weight:800;font-size:1.3rem;color:#fff;text-decoration:none;letter-spacing:.2px}
.brand:hover{color:#fff}
.menu-pill{color:#ddd6fe;text-decoration:none;padding:.45rem 1rem;border-radius:999px;font-size:.88rem;font-weight:600;border:1px solid transparent}
.menu-pill:hover{color:#fff;background:rgba(255,255,255,.12)}
.menu-pill.active{background:#fff;color:#4c1d95}
.page-head{margin:0 0 1.1rem}
.page-head h4{font-weight:800;margin:0}
.page-head p{color:var(--muted);margin:.15rem 0 0;font-size:.9rem}
.card{border:1px solid var(--line);border-radius:18px;box-shadow:0 4px 18px rgba(15,23,42,.06)}
.card-header{background:#fff!important;border-bottom:1px solid var(--line);border-radius:18px 18px 0 0!important;font-weight:700;padding:.9rem 1.2rem}
.card-body{padding:1.2rem}
.stat{border-radius:18px;color:#fff;padding:1.1rem 1.2rem;box-shadow:0 6px 18px rgba(15,23,42,.14);position:relative;overflow:hidden}
.stat i{position:absolute;right:.9rem;top:.7rem;font-size:1.8rem;opacity:.35}
.stat small{opacity:.9;font-weight:600;letter-spacing:.4px;text-transform:uppercase;font-size:.68rem}
.stat h3{margin:.15rem 0 0;font-weight:800;font-size:1.65rem}
.stat-green{background:linear-gradient(135deg,#047857,#34d399)}
.stat-red{background:linear-gradient(135deg,#b91c1c,#fb7185)}
.stat-blue{background:linear-gradient(135deg,#1d4ed8,#60a5fa)}
.stat-gold{background:linear-gradient(135deg,#b45309,#fbbf24)}
.stat-violet{background:linear-gradient(135deg,#6d28d9,#a78bfa)}
.table{margin:0}
.table thead th{background:#f8fafc;color:var(--muted);font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;border:0;padding:.7rem 1rem}
.table tbody td{padding:.8rem 1rem;vertical-align:middle;border-color:var(--line)}
.table tbody tr:last-child td{border-bottom:0}
.btn{border-radius:10px;font-weight:600}
.btn-dark{background:#1e1b4b;border-color:#1e1b4b}
.form-label{font-weight:600;font-size:.85rem}
.form-control,.form-select{border-radius:10px;padding:.6rem .8rem;border-color:#dbe1ec}
.form-control:focus,.form-select:focus{border-color:#6d28d9;box-shadow:0 0 0 .2rem rgba(109,40,217,.12)}
.badge{font-weight:600;padding:.4em .7em;border-radius:999px}
.alert{border-radius:14px;border:0}
code{background:#ede9fe;color:#5b21b6;padding:.2rem .5rem;border-radius:8px}
.footer{color:var(--muted);font-size:.8rem}
.avatar{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#4c1d95,#7c3aed);color:#fff;font-weight:800;font-size:.9rem;margin-right:.3rem}
.chip{display:inline-block;font-size:.72rem;font-weight:700;padding:.25em .7em;border-radius:999px;margin:0 .2rem .2rem 0}
.chip-work{background:#fef3c7;color:#92400e}
.dot{display:inline-block;width:12px;height:12px;border-radius:50%}
.progress{border-radius:99px;background:#e8ecf3}
.section-card-emoji{font-size:2.6rem}
.auth-card{max-width:460px;margin:3rem auto}
</style>
</head>
<body>
<div class="topbar mb-4"><div class="container py-3">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <a class="brand" href="{{ session()->has('admin_id') ? route('dashboard') : route('funder.code') }}"><i class="bi bi-wallet2 me-1"></i>Fund Tracker</a>
    @if(session()->has('admin_id'))
    <span class="small text-white-50"><i class="bi bi-person-circle me-1"></i>{{ session('admin_name') }} <form method="POST" action="{{ route('logout') }}" class="d-inline ms-1">@csrf<button class="btn btn-sm btn-outline-light">Logout</button></form></span>
    @else
    <a class="menu-pill" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i>Admin Login</a>
    @endif
  </div>
  @if(session()->has('admin_id'))
  <nav class="d-flex gap-1 mt-3 flex-wrap">
    <a class="menu-pill {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2 me-1"></i>Dashboard</a>
    <a class="menu-pill {{ request()->routeIs('funds.*') ? 'active' : '' }}" href="{{ route('funds.index') }}"><i class="bi bi-cash-stack me-1"></i>Funds</a>
    <a class="menu-pill {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}"><i class="bi bi-receipt me-1"></i>Expenses</a>
    <a class="menu-pill {{ request()->routeIs('works.*') ? 'active' : '' }}" href="{{ route('works.index') }}"><i class="bi bi-hammer me-1"></i>Works</a>
    <a class="menu-pill {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}"><i class="bi bi-tags me-1"></i>Categories</a>
    <a class="menu-pill {{ request()->routeIs('invites.*') ? 'active' : '' }}" href="{{ route('invites.index') }}"><i class="bi bi-envelope-paper me-1"></i>Invites</a>
  </nav>
  @endif
</div></div>
<div class="container pb-5">
  @if(session('ok'))<div class="alert alert-success"><i class="bi bi-check-circle me-1"></i>{{ session('ok') }}</div>@endif
  @if(session('error'))<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-1"></i>{{ session('error') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @yield('content')
  <p class="text-center footer mt-4">Fund Tracker • live project accounts</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>
