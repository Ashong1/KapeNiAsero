@extends('layouts.app')

@section('styles')
<style>
    /* Dashboard Specific Styles */
    .kpi-card {
        position: relative; overflow: hidden; border: none; border-radius: 20px;
        background: white; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
        height: 100%;
    }
    .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 15px 50px -10px rgba(0,0,0,0.12); }
    
    .kpi-bg-icon {
        position: absolute; right: -10px; bottom: -10px; font-size: 5rem;
        opacity: 0.05; transform: rotate(-15deg); z-index: 0;
    }

    .card-content-wrapper { position: relative; z-index: 1; height: 100%; display: flex; flex-direction: column; }

    .table-card-header {
        background: transparent; border-bottom: 1px solid var(--border-light);
        padding: 1.2rem 1.5rem; display: flex; justify-content: space-between; align-items: center;
    }

    .progress-thin { height: 6px; border-radius: 3px; }
    .badge-soft-void { background-color: #FEF2F2; color: #DC2626; }
    
    .avatar-circle {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; color: white; font-size: 1.1rem;
    }
    
    .chart-container { position: relative; height: 300px; width: 100%; }

    /* Nav Tabs Custom */
    .nav-tabs-custom { border-bottom: none; }
    .nav-tabs-custom .nav-link {
        border: none; color: var(--text-secondary); font-weight: 600; font-size: 0.9rem;
        background: transparent; padding: 0.5rem 1rem; border-radius: 20px;
    }
    .nav-tabs-custom .nav-link.active {
        background-color: var(--surface-bg); color: var(--primary-coffee);
    }
</style>
@endsection

@section('content')
<div class="container">

    {{-- WELCOME SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Dashboard</h4>
            <p class="text-secondary small m-0">Overview & Statistics</p>
        </div>
        <div class="d-none d-md-block">
            <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill shadow-sm">
                <i class="far fa-calendar-alt me-1"></i> {{ now()->format('l, F d, Y') }}
            </span>
        </div>
    </div>
    
<<<<<<< HEAD
    {{-- SHIFT STATUS ALERT --}}
    <div class="mb-4">
        @if(isset($activeShift))
            <div class="alert alert-success d-flex justify-content-between align-items-center shadow-sm border-0 mb-0" role="alert">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white text-success p-2 me-3 shadow-sm">
                        <i class="fas fa-cash-register fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold m-0">Your Register is OPEN</h6> 
                        <span class="text-success-emphasis small">Started at {{ $activeShift->started_at->format('h:i A') }}</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('shifts.edit', $activeShift->id) }}" class="btn btn-danger fw-bold shadow-sm">End Shift</a>
                </div>
            </div>
        @else
            <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm border-0 mb-0" role="alert">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white text-warning p-2 me-3 shadow-sm">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold m-0 text-dark">Register is CLOSED</h6> 
                        <span class="text-secondary small">You must open the register to record your own sales.</span>
                    </div>
                </div>
                <a href="{{ route('shifts.create') }}" class="btn btn-warning text-white fw-bold shadow-sm">Open Register</a>
            </div>
        @endif
    </div>
=======
    {{-- SHIFT STATUS ALERT (EMPLOYEES ONLY) --}}
    @if(Auth::user()->role !== 'admin')
        <div class="mb-4">
            @if(isset($activeShift))
                <div class="alert alert-success d-flex justify-content-between align-items-center shadow-sm border-0" role="alert">
                    <div>
                        <i class="fas fa-cash-register me-2"></i>
                        <strong>Register OPEN</strong> 
                        <span class="text-muted ms-2 small">Started: {{ $activeShift->started_at->format('M d, h:i A') }}</span>
                    </div>
                    <a href="{{ route('shifts.edit', $activeShift->id) }}" class="btn btn-sm btn-danger fw-bold shadow-sm">
                        End Shift
                    </a>
                </div>
            @else
                <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm border-0" role="alert">
                    <div>
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Register CLOSED</strong> 
                        <span class="text-muted ms-2 small">You must open the register to record sales accurately.</span>
                    </div>
                    <a href="{{ route('shifts.create') }}" class="btn btn-sm btn-primary fw-bold shadow-sm">
                        Open Register
                    </a>
                </div>
            @endif
        </div>
    @endif
>>>>>>> b3ca99ddefa3fef3cfea4198400f1ff8bd18a02a

    {{-- KPI CARDS ROW (4 Columns) --}}
    <div class="row mb-4 g-3">
        {{-- 1. Sales KPI --}}
        <div class="col-md-6 col-xl-3">
            <div class="card kpi-card p-4">
                <i class="fas fa-coins kpi-bg-icon text-warning"></i>
                <div class="card-content-wrapper">
                    <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Total Sales</span>
                    <h3 class="fw-bold mb-0 text-dark">₱{{ number_format($todaySales ?? 0, 0) }}</h3>
                    <div class="mt-auto pt-2 text-success small fw-medium">
                        <i class="fas fa-chart-line me-1"></i> Today's Revenue
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Orders KPI (UPDATED WITH PARKED & TYPES) --}}
        <div class="col-md-6 col-xl-3">
            <div class="card kpi-card p-4">
                <i class="fas fa-receipt kpi-bg-icon text-primary"></i>
                <div class="card-content-wrapper">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase text-secondary fw-bold small tracking-wide">Orders</span>
                        @if($parkedCount > 0)
                            <span class="badge bg-warning text-dark border border-warning-subtle" title="Parked Orders">
                                <i class="fas fa-pause-circle me-1"></i> {{ $parkedCount }} On Hold
                            </span>
                        @endif
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $todayOrders ?? 0 }}</h3>
                    
                    {{-- Dine In vs Take Out Bar --}}
                    <div class="mt-auto pt-2">
                        @if($todayOrders > 0)
                            <div class="progress progress-thin mb-1" style="height: 4px;">
                                <div class="progress-bar bg-primary-coffee" style="width: {{ ($orderStats->dine_in / max(1, $todayOrders)) * 100 }}%"></div>
                                <div class="progress-bar bg-secondary" style="width: {{ ($orderStats->take_out / max(1, $todayOrders)) * 100 }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted" style="font-size: 0.7rem;">
                                <span>{{ $orderStats->dine_in }} Dine-in</span>
                                <span>{{ $orderStats->take_out }} Take-out</span>
                            </div>
                        @else
                            <div class="text-secondary small">No orders yet</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Top Performer KPI --}}
        <div class="col-md-6 col-xl-3">
            <div class="card kpi-card p-4" style="border-bottom: 4px solid var(--success-green);">
                <i class="fas fa-trophy kpi-bg-icon text-success"></i>
                <div class="card-content-wrapper">
                    <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Top Star</span>
                    @if($topServer)
                        <h5 class="fw-bold mb-0 text-dark text-truncate">{{ $topServer->user->name }}</h5>
                        <div class="mt-auto pt-2 text-success small fw-bold">
                            ₱{{ number_format($topServer->total_sales, 0) }} <span class="text-muted fw-normal">({{ $topServer->order_count }} Orders)</span>
                        </div>
                    @else
                        <h5 class="fw-bold mb-0 text-muted">--</h5>
                        <div class="mt-auto pt-2 text-secondary small">No sales yet</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 4. Stock KPI --}}
        <div class="col-md-6 col-xl-3">
            <div class="card kpi-card p-4" style="border-bottom: 4px solid var(--danger-red);">
                <i class="fas fa-exclamation-triangle kpi-bg-icon text-danger"></i>
                <div class="card-content-wrapper">
                    <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Low Stock</span>
                    <h3 class="fw-bold mb-0" style="color: var(--danger-red);">{{ $lowStockIngredients->count() }}</h3>
                    <div class="mt-auto pt-2 text-danger small fw-medium">
                        <i class="fas fa-bell me-1"></i> Action Needed
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT ROW --}}
    <div class="row g-4 mb-4">
        {{-- Left Column: Charts --}}
        <div class="col-lg-8">
            <div class="card card-custom h-100 mb-4">
                <div class="table-card-header border-0 pb-0">
                    <h5 class="m-0 fw-bold text-dark"><i class="fas fa-chart-area me-2 text-primary-coffee"></i>Weekly Analytics</h5>
                    <span class="badge bg-light text-secondary border">Last 7 Days</span>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Active Staff & Stock --}}
        <div class="col-lg-4">
            <div class="card card-custom mb-4">
                <div class="table-card-header bg-success-subtle bg-opacity-10">
                    <h5 class="m-0 fw-bold text-success"><i class="fas fa-users me-2"></i>Who is Working?</h5>
                    <span class="badge bg-success rounded-pill">{{ $activeStaff->count() }} Active</span>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($activeStaff as $shift)
                    <div class="list-group-item p-3 border-light d-flex align-items-center">
                        <div class="avatar-circle bg-primary-coffee me-3">
                            {{ substr($shift->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark">{{ $shift->user->name }}</div>
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i> In since {{ $shift->started_at->format('h:i A') }}
                            </small>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">On Duty</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted small">
                        <i class="fas fa-store-slash fa-2x mb-2 opacity-50"></i><br>
                        No active shifts right now.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- CRITICAL STOCK LIST --}}
            <div class="card card-custom h-100">
                <div class="table-card-header bg-danger-subtle bg-opacity-10">
                    <h5 class="m-0 fw-bold text-danger"><i class="fas fa-clipboard-list me-2"></i>Critical Stock</h5>
                    <a href="{{ route('ingredients.index') }}" class="btn btn-sm btn-outline-danger rounded-pill px-2" style="font-size: 0.7rem;">Check</a>
                </div>
                <div class="list-group list-group-flush overflow-auto" style="max-height: 250px;">
                    @forelse($lowStockIngredients as $ing)
                    <div class="list-group-item p-3 border-light">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="fw-bold text-dark">{{ $ing->name }}</span>
                            <span class="badge bg-danger rounded-pill">{{ $ing->stock }} {{ $ing->unit }}</span>
                        </div>
                        <div class="progress progress-thin mt-2">
                            <div class="progress-bar bg-danger" style="width: {{ min(100, ($ing->stock / max(1, $ing->reorder_level)) * 50) }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-success small">
                        <i class="fas fa-check-circle fa-2x mb-2 opacity-50"></i><br>
                        Inventory is healthy.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT ACTIVITY WITH TABS --}}
    <div class="card card-custom mb-4">
        <div class="table-card-header">
            <h5 class="m-0 fw-bold text-dark"><i class="fas fa-clock me-2 text-secondary"></i>Live Activity Feed</h5>
            
            {{-- Tabs for Sales vs System Logs --}}
            <ul class="nav nav-tabs-custom" id="activityTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales-pane" type="button">Transactions</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system-pane" type="button">System Logs</button>
                </li>
            </ul>
        </div>
        
        <div class="card-body p-0">
            <div class="tab-content" id="activityTabContent">
                {{-- TAB 1: SALES --}}
                <div class="tab-pane fade show active" id="sales-pane" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Barista</th>
                                    <th>Time</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr class="{{ $order->status === 'voided' ? 'text-muted fst-italic' : '' }}">
                                    <td class="ps-4 fw-bold font-monospace">#{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td class="small text-secondary">{{ $order->created_at->format('h:i A') }}</td>
                                    <td class="fw-bold {{ $order->status !== 'voided' ? 'text-success' : '' }}">₱{{ number_format($order->total_price, 2) }}</td>
                                    <td>
                                        @if($order->status === 'voided')
                                            <span class="badge badge-soft-void rounded-pill">Voided</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Paid</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($order->status !== 'voided')
                                            <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" class="btn btn-sm btn-light border" title="Receipt"><i class="fas fa-print"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-5 text-muted">No transactions yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: SYSTEM LOGS --}}
                <div class="tab-pane fade" id="system-pane" role="tabpanel">
                    <div class="list-group list-group-flush">
                        @forelse($recentLogs as $log)
                        <div class="list-group-item p-3">
                            <div class="d-flex justify-content-between">
                                <div class="fw-bold text-dark">{{ $log->action }} <span class="fw-normal text-muted">by {{ $log->user->name }}</span></div>
                                <small class="text-secondary">{{ $log->created_at->diffForHumans() }}</small>
                            </div>
                            <small class="text-muted d-block">{{ $log->details }}</small>
                        </div>
                        @empty
                        <div class="text-center py-5 text-muted">No system activity logs found.</div>
                        @endforelse
                    </div>
                    <div class="p-2 text-center border-top bg-light">
                        <a href="{{ route('activity-logs.index') }}" class="small text-decoration-none fw-bold">View All Logs &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart');
    const labels = @json($salesLabels);
    const data = @json($salesData);

    if(ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Daily Sales',
                    data: data,
                    borderColor: '#6F4E37',
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        if (!chartArea) return null;
                        
                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, 'rgba(111, 78, 55, 0.05)');
                        gradient.addColorStop(1, 'rgba(111, 78, 55, 0.4)');
                        return gradient;
                    },
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#FFF',
                    pointBorderColor: '#6F4E37',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] }, ticks: { callback: v => '₱' + v } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
</script>
@endsection