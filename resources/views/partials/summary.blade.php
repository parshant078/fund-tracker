@php $usedPct = $totalFunds > 0 ? min(100, round($totalExpenses / $totalFunds * 100)) : 0; @endphp
<div class="hero-band p-4 p-md-5 mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 position-relative" style="z-index:1">
    <div><div class="hero-label">Project Overview • live</div><h2 class="fw-bold mb-0">Money & Work, at a glance</h2></div>
    <span class="badge text-bg-light"><i class="bi bi-lightning-charge me-1"></i>{{ $usedPct }}% funds used</span>
  </div>
  <div class="row mt-4 position-relative" style="z-index:1">
    <div class="col-4"><div class="hero-label"><i class="bi bi-arrow-down-circle me-1"></i>Received</div><p class="hero-num">₹{{ number_format($totalFunds, 0) }}</p></div>
    <div class="col-4 hero-div ps-3"><div class="hero-label"><i class="bi bi-arrow-up-circle me-1"></i>Spent</div><p class="hero-num">₹{{ number_format($totalExpenses, 0) }}</p></div>
    <div class="col-4 hero-div ps-3"><div class="hero-label"><i class="bi bi-piggy-bank me-1"></i>In Hand</div><p class="hero-num">₹{{ number_format($totalFunds - $totalExpenses, 0) }}</p></div>
  </div>
  <div class="progress mt-3 position-relative" style="height:9px;background:rgba(255,255,255,.22);z-index:1"><div class="progress-bar bg-warning" style="width:{{ $usedPct }}%"></div></div>
</div>
<div class="row g-3 mb-3">
  <div class="col-md-5"><div class="card h-100"><div class="card-header"><i class="bi bi-pie-chart me-1 text-primary"></i>Spending by Category</div><div class="card-body">
    @if($byCategory->isEmpty())<p class="text-muted mb-0">No expenses yet.</p>
    @else<canvas id="catChart" height="220"></canvas>@endif
  </div></div></div>
  <div class="col-md-7"><div class="card h-100"><div class="card-header"><i class="bi bi-bar-chart me-1 text-primary"></i>Last 6 Months: Received vs Spent</div><div class="card-body"><canvas id="trendChart" height="220"></canvas></div></div></div>
</div>
<div class="card mb-3"><div class="card-header"><i class="bi bi-signpost-2 me-1 text-primary"></i>Work Pipeline — plan to done</div><div class="card-body">
@forelse($works as $w)
@php $spent = (float)($w->expenses_sum_amount ?? 0); $pct = $w->budget > 0 ? min(100, round($spent / $w->budget * 100)) : 0; @endphp
<div class="border rounded-4 p-3 mb-3 work-accent work-{{ $w->status }}">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h5 class="mb-0">{{ $w->title }}</h5>
    @if($w->status == 'completed')<span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i>Completed</span>
    @elseif($w->status == 'in_progress')<span class="badge text-bg-warning"><i class="bi bi-arrow-repeat me-1"></i>In Progress</span>
    @else<span class="badge text-bg-primary"><i class="bi bi-calendar-event me-1"></i>Planned</span>@endif
  </div>
  <div class="stepper">
    <div class="step done"><span class="sdot"><i class="bi bi-check"></i></span>Planned</div>
    <div class="step {{ $w->status == 'in_progress' ? 'now' : ($w->status == 'completed' ? 'done' : '') }}"><span class="sdot">@if($w->status == 'completed')<i class="bi bi-check"></i>@elseif($w->status == 'in_progress')<i class="bi bi-hammer"></i>@else 2 @endif</span>In Progress</div>
    <div class="step {{ $w->status == 'completed' ? 'done' : '' }}"><span class="sdot">@if($w->status == 'completed')<i class="bi bi-check"></i>@else 3 @endif</span>Completed</div>
  </div>
  <div class="d-flex gap-3 flex-wrap small">
    <span><span class="text-muted">Budget</span> <b>₹{{ number_format($w->budget, 0) }}</b></span>
    <span><span class="text-muted">Spent</span> <b class="text-danger">₹{{ number_format($spent, 0) }}</b></span>
    <span><span class="text-muted">Advance</span> <b class="text-primary">₹{{ number_format($w->advance_paid, 0) }}</b></span>
  </div>
  <div class="progress mt-2" style="height:7px"><div class="progress-bar {{ $pct >= 100 ? 'bg-danger' : ($pct >= 70 ? 'bg-warning' : 'bg-success') }}" style="width:{{ $pct }}%"></div></div>
  @if($w->notes)<p class="text-muted small mt-2 mb-0"><i class="bi bi-arrow-right-circle me-1"></i><b>Next:</b> {{ $w->notes }}</p>@endif
</div>
@empty<p class="text-muted mb-0">No works added yet.</p>@endforelse
</div></div>
@push('scripts')
<script>
@if($byCategory->isNotEmpty())
new Chart(document.getElementById('catChart'), {type:'doughnut',
data:{labels:{!! json_encode($byCategory->pluck('name')) !!},datasets:[{data:{!! json_encode($byCategory->pluck('total')) !!},backgroundColor:['#4f46e5','#059669','#e11d48','#d97706','#7c3aed','#0891b2','#db2777','#65a30d'],borderWidth:3,borderColor:'#fff'}]},
options:{cutout:'62%',plugins:{legend:{position:'bottom',labels:{boxWidth:12,padding:14}},tooltip:{callbacks:{label:c=>' ₹'+Number(c.raw).toLocaleString('en-IN')}}}}});
@endif
new Chart(document.getElementById('trendChart'), {type:'bar',
data:{labels:{!! json_encode($trend['labels']) !!},datasets:[
{label:'Received',data:{!! json_encode($trend['funds']) !!},backgroundColor:'#059669',borderRadius:6},
{label:'Spent',data:{!! json_encode($trend['expenses']) !!},backgroundColor:'#f43f5e',borderRadius:6}]},
options:{plugins:{legend:{position:'bottom',labels:{boxWidth:12}}},scales:{y:{beginAtZero:true,grid:{color:'#eef1f6'},ticks:{callback:v=>'₹'+Number(v).toLocaleString('en-IN')}}},x:{grid:{display:false}}}});
</script>
@endpush
