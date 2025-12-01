@extends('layouts.app')

@section('styles')
<style>
    /* Dashboard Specific Styles */
    .kpi-card {
        position: relative; overflow: hidden; border: none; border-radius: 20px;
        background: white; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
        height: 100%;
        cursor: pointer; /* Makes cards look clickable */
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
    
    .chart-container { position: relative; height: 250px; width: 100%; }

    /* Nav Tabs Custom */
    .nav-tabs-custom { border-bottom: none; }
    .nav-tabs-custom .nav-link {
        border: none; color: var(--text-secondary); font-weight: 600; font-size: 0.9rem;
        background: transparent; padding: 0.5rem 1rem; border-radius: 20px;
        cursor: pointer;
    }
    .nav-tabs-custom .nav-link.active {
        background-color: var(--surface-bg); color: var(--primary-coffee);
    }

    /* --- BRANDED ACTION BUTTONS --- */
    .btn-icon { 
        width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; 
        border-radius: 10px; transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1); 
        border: none; font-size: 0.85rem; margin-left: 4px; text-decoration: none; 
        cursor: pointer;
    }
    
    .btn-icon-print { background-color: #F3F4F6; color: #4B5563; }
    .btn-icon-print:hover { background-color: var(--primary-coffee); color: white; transform: translateY(-2px); }

    /* Custom Buttons for Shift/Stock */
    .btn-brand-coffee { background: var(--primary-coffee); color: white; border: none; box-shadow: 0 4px 10px rgba(111, 78, 55, 0.2); transition: all 0.2s; cursor: pointer; }
    .btn-brand-coffee:hover { background: var(--primary-coffee-hover); color: white; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(111, 78, 55, 0.3); }

    .btn-brand-danger { background: #DC2626; color: white; border: none; box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2); transition: all 0.2s; cursor: pointer; }
    .btn-brand-danger:hover { background: #B91C1C; color: white; transform: translateY(-2px); }

    .btn-brand-outline-danger { background: transparent; border: 1px solid #DC2626; color: #DC2626; transition: all 0.2s; cursor: pointer; }
    .btn-brand-outline-danger:hover { background: #FEF2F2; color: #B91C1C; }

    /* Rank Badges for Best Sellers */
    .rank-badge { width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.75rem; font-weight: bold; margin-right: 10px; }
    .rank-1 { background-color: #FEF08A; color: #854D0E; border: 1px solid #FDE047; }
    .rank-2 { background-color: #E5E7EB; color: #374151; border: 1px solid #D1D5DB; }
    .rank-3 { background-color: #FDBA74; color: #9A3412; border: 1px solid #FB923C; }
    
    /* Hover effects for clickable cards */
    .card-link { text-decoration: none; color: inherit; display: block; height: 100%; }
    .card-link:hover { text-decoration: none; color: inherit; }
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
                    {{-- End Shift with Confirmation --}}
                    <a href="{{ route('shifts.edit', $activeShift->id) }}" id="btn-end-shift" class="btn btn-sm btn-brand-danger fw-bold rounded-pill px-3">
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
                    {{-- Open Register --}}
                    <a href="{{ route('shifts.create') }}" class="btn btn-sm btn-brand-coffee fw-bold rounded-pill px-3">
                        Open Register
                    </a>
                </div>
            @endif
        </div>
    @endif

    {{-- KPI CARDS ROW (4 Columns) --}}
    <div class="row mb-4 g-3">
        
        {{-- 1. Sales KPI -> Redirects to Reports --}}
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('reports.index') }}" class="card-link" title="View Sales Reports">
                <div class="card kpi-card p-4">
                    <i class="fas fa-coins kpi-bg-icon text-warning"></i>
                    <div class="card-content-wrapper">
                        <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Total Sales</span>
                        <h3 class="fw-bold mb-0 text-dark">₱{{ number_format($todaySales ?? 0, 0) }}</h3>
                        
                        <div class="mt-3 d-flex justify-content-between align-items-end">
                            <div class="text-success small fw-medium">
                                <i class="fas fa-chart-line me-1"></i> AOV: ₱{{ number_format($averageOrderValue ?? 0, 0) }}
                            </div>
                            @if(($todayDiscounts ?? 0) > 0)
                                <div class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill" title="Total Discounts Given">
                                    -₱{{ number_format($todayDiscounts, 0) }} Off
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- 2. Orders KPI -> Redirects to Order History --}}
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('orders.index') }}" class="card-link" title="View Order History">
                <div class="card kpi-card p-4">
                    <i class="fas fa-receipt kpi-bg-icon text-primary"></i>
                    <div class="card-content-wrapper">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-uppercase text-secondary fw-bold small tracking-wide">Orders</span>
                            @if($parkedCount > 0)
                                <span class="badge bg-warning text-dark border border-warning-subtle" title="Parked Orders">
                                    <i class="fas fa-pause-circle me-1"></i> {{ $parkedCount }} Hold
                                </span>
                            @endif
                        </div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $todayOrders ?? 0 }}</h3>
                        
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

                            {{-- VOID ALERT --}}
                            @if(($voidStats->count ?? 0) > 0)
                                <div class="mt-2 pt-2 border-top border-light text-danger small fw-bold">
                                    <i class="fas fa-ban me-1"></i> {{ $voidStats->count }} Voids (₱{{ number_format($voidStats->total_amount, 0) }})
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- 3. Top Performer KPI -> Redirects to Reports (Performance) --}}
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('reports.index') }}" class="card-link" title="View Performance Reports">
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
            </a>
        </div>

        {{-- 4. Critical Stock KPI -> Redirects to Ingredients --}}
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('ingredients.index') }}" class="card-link" title="View Ingredients Inventory">
                <div class="card kpi-card p-4" style="border-bottom: 4px solid var(--danger-red);">
                    <i class="fas fa-exclamation-triangle kpi-bg-icon text-danger"></i>
                    <div class="card-content-wrapper">
                        <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Critical Stock</span>
                        <h3 class="fw-bold mb-0" style="color: var(--danger-red);">{{ $lowStockIngredients->count() }} Items</h3>
                        <div class="mt-auto pt-2 text-danger small fw-medium">
                            <i class="fas fa-arrow-right me-1"></i> Check Inventory
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- MAIN CONTENT ROW --}}
    <div class="row g-4 mb-4">
        {{-- Left Column: Charts & Financials --}}
        <div class="col-lg-8">
            
            {{-- 1. Weekly Analytics Chart --}}
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

            {{-- 2. Financial Breakdown --}}
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card card-custom">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap">
                            <div>
                                <h6 class="fw-bold text-secondary text-uppercase small mb-3">Payment Methods (Today)</h6>
                                <div class="d-flex align-items-center gap-4">
                                    {{-- Cash Stat --}}
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success-subtle text-success rounded-circle p-2 me-2">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div>
                                            <div class="h5 fw-bold mb-0">₱{{ number_format($paymentStats['cash'] ?? 0, 0) }}</div>
                                            <small class="text-muted">Cash</small>
                                        </div>
                                    </div>
                                    {{-- Digital Stat --}}
                                    <div class="d-flex align-items-center border-start ps-4">
                                        <div class="bg-primary-subtle text-primary rounded-circle p-2 me-2">
                                            <i class="fas fa-mobile-alt"></i>
                                        </div>
                                        <div>
                                            @php 
                                                $digitalTotal = ($paymentStats['gcash'] ?? 0) + ($paymentStats['card'] ?? 0);
                                            @endphp
                                            <div class="h5 fw-bold mb-0">₱{{ number_format($digitalTotal, 0) }}</div>
                                            <small class="text-muted">Digital</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Visual Bar --}}
                            <div class="flex-grow-1 ms-md-5 mt-3 mt-md-0" style="max-width: 300px;">
                                @php $totalPay = ($todaySales > 0) ? $todaySales : 1; @endphp
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ (($paymentStats['cash'] ?? 0) / $totalPay) * 100 }}%"></div>
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($digitalTotal / $totalPay) * 100 }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between small text-muted mt-1">
                                    <span>Cash {{ round((($paymentStats['cash'] ?? 0) / $totalPay) * 100) }}%</span>
                                    <span>Digital {{ round(($digitalTotal / $totalPay) * 100) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Top Products & Staff --}}
        <div class="col-lg-4">
            
            {{-- 1. BEST SELLERS --}}
            <div class="card card-custom mb-4">
                <div class="table-card-header bg-warning-subtle bg-opacity-10">
                    <h5 class="m-0 fw-bold text-dark"><i class="fas fa-star me-2 text-warning"></i>Best Sellers</h5>
                    <span class="badge bg-warning text-dark border border-warning-subtle">Today</span>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($topProducts ?? [] as $index => $item)
                        <div class="list-group-item p-3 border-light d-flex align-items-center">
                            <span class="rank-badge {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : 'rank-3') }}">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark text-truncate" style="max-width: 150px;">{{ $item->product->name }}</div>
                                <div class="small text-muted">{{ $item->total_sold }} sold</div>
                            </div>
                            <div class="fw-bold text-success">
                                ₱{{ number_format($item->total_sold * $item->product->price, 0) }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">No sales data yet.</div>
                    @endforelse
                </div>
            </div>

            {{-- 2. Active Staff --}}
            <div class="card card-custom mb-4">
                <div class="table-card-header bg-success-subtle bg-opacity-10">
                    <h5 class="m-0 fw-bold text-success"><i class="fas fa-users me-2"></i>On Duty</h5>
                    <span class="badge bg-success rounded-pill">{{ $activeStaff->count() }}</span>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($activeStaff as $shift)
                    <div class="list-group-item p-3 border-light d-flex align-items-center">
                        <div class="avatar-circle bg-primary-coffee me-3">
                            {{ substr($shift->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark">{{ $shift->user->name }}</div>
                            <small class="text-muted">In: {{ $shift->started_at->format('h:i A') }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted small">No active shifts.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT ACTIVITY WITH TABS --}}
    <div class="card card-custom mb-4">
        <div class="table-card-header">
            <h5 class="m-0 fw-bold text-dark"><i class="fas fa-clock me-2 text-secondary"></i>Live Activity Feed</h5>
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
                {{-- SALES TAB --}}
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
                                            <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" class="btn-icon btn-icon-print" title="Receipt">
                                                <i class="fas fa-print"></i>
                                            </a>
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

                {{-- SYSTEM LOGS TAB --}}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. Chart Logic
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

    // 2. End Shift Confirmation Logic
    document.addEventListener('DOMContentLoaded', function() {
        const endShiftBtn = document.getElementById('btn-end-shift');
        if(endShiftBtn) {
            endShiftBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                
                Swal.fire({
                    title: 'End Shift?',
                    text: "You are about to close the register. Make sure you have counted the cash.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Yes, End Shift'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        }
    });
</script>
@endsection