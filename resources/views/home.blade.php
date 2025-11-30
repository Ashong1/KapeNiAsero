@extends('layouts.app')

@section('styles')
<style>
    /* Dashboard Specific Styles */
    .kpi-card {
        position: relative; overflow: hidden; border: none; border-radius: 20px;
        background: white; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
    }
    .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 15px 50px -10px rgba(0,0,0,0.12); }
    
    .kpi-bg-icon {
        position: absolute; right: -10px; bottom: -10px; font-size: 5rem;
        opacity: 0.05; transform: rotate(-15deg); z-index: 0;
    }

    .card-content-wrapper { position: relative; z-index: 1; height: 100%; display: flex; flex-direction: column; }

    .table-card-header {
        background: transparent; border-bottom: 1px solid var(--border-light);
        padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;
    }

    /* Shortcut Buttons */
    .btn-shortcut {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 1.5rem; border-radius: 16px; background: white; border: 1px solid var(--border-light);
        color: var(--text-dark); transition: all 0.2s; height: 100%; text-decoration: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    }
    .btn-shortcut:hover {
        transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        background: #FAFAFA; color: var(--primary-coffee);
    }
    .btn-shortcut i { font-size: 1.8rem; margin-bottom: 0.8rem; color: var(--primary-coffee); }
    
    /* Audit Log Badge Styles */
    .badge-soft-void { background-color: #FEF2F2; color: #DC2626; }
    .badge-soft-stock { background-color: #FFFBEB; color: #D97706; }
    .badge-soft-system { background-color: #F3F4F6; color: #4B5563; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- WELCOME SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0">Dashboard</h4>
            <p class="text-secondary small m-0">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
        <div class="d-none d-md-block">
            <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill">
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

    {{-- KPI CARDS ROW --}}
    <div class="row mb-4 g-4">
        <div class="col-md-4">
            <div class="card kpi-card h-100 p-4">
                <i class="fas fa-coins kpi-bg-icon text-warning"></i>
                <div class="card-content-wrapper">
                    <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Total Sales Today</span>
                    <h2 class="display-6 fw-bold mb-0 text-dark">₱{{ number_format($todaySales ?? 0, 2) }}</h2>
                    <div class="mt-auto pt-3 text-success small fw-medium">
                        <i class="fas fa-arrow-up me-1"></i> Keep it brewing!
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card kpi-card h-100 p-4">
                <i class="fas fa-receipt kpi-bg-icon text-primary"></i>
                <div class="card-content-wrapper">
                    <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Transactions</span>
                    <h2 class="display-6 fw-bold mb-0 text-dark">{{ $todayOrders ?? 0 }}</h2>
                    <div class="mt-auto pt-3 text-primary-coffee small fw-medium">
                        <i class="fas fa-coffee me-1"></i> Orders served
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card kpi-card h-100 p-4" style="border-bottom: 4px solid var(--danger-red);">
                <i class="fas fa-exclamation-triangle kpi-bg-icon text-danger"></i>
                <div class="card-content-wrapper">
                    <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Low Stock Alerts</span>
                    <h2 class="display-6 fw-bold mb-0" style="color: var(--danger-red);">{{ $lowStockIngredients->count() }}</h2>
                    <div class="mt-auto pt-3 text-danger small fw-medium">
                        <i class="fas fa-bell me-1"></i> Action needed
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- SALES CHART --}}
        <div class="col-lg-8">
            <div class="card card-custom h-100">
                <div class="table-card-header border-0 pb-0">
                    <h5 class="m-0 fw-bold text-dark"><i class="fas fa-chart-line me-2 text-primary-coffee"></i>Weekly Analytics</h5>
                    <span class="badge bg-light text-secondary border">Last 7 Days</span>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" style="height: 250px; width: 100%;"></canvas>
                </div>
            </div>
        </div>

        {{-- QUICK SHORTCUTS --}}
        <div class="col-lg-4">
            <div class="row g-3 h-100">
                <div class="col-6">
                    <a href="{{ route('products.index') }}" class="btn-shortcut">
                        <i class="fas fa-cash-register"></i>
                        <span class="fw-bold small">POS Terminal</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('products.create') }}" class="btn-shortcut">
                        <i class="fas fa-plus-circle"></i>
                        <span class="fw-bold small">Add Product</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('ingredients.index') }}" class="btn-shortcut">
                        <i class="fas fa-boxes"></i>
                        <span class="fw-bold small">Inventory</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('orders.index') }}" class="btn-shortcut">
                        <i class="fas fa-history"></i>
                        <span class="fw-bold small">History</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- RECENT ACTIVITY TABLE --}}
        <div class="col-lg-8">
            <div class="card card-custom h-100">
                <div class="table-card-header">
                    <h5 class="m-0 fw-bold text-dark">Recent Activity</h5>
                    <span class="badge bg-light text-dark border">Today</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Order ID</th>
                                <th>Barista</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr class="{{ $order->status === 'voided' ? 'text-muted fst-italic' : '' }}">
                                <td class="ps-4 fw-bold font-monospace">#{{ $order->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary" style="width:24px;height:24px;font-size:0.7rem;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        {{ $order->user->name }}
                                    </div>
                                </td>
                                <td>
                                    @if($order->status === 'voided')
                                        <span class="text-decoration-line-through">₱{{ number_format($order->total_price, 2) }}</span>
                                    @else
                                        <span class="fw-bold text-success">₱{{ number_format($order->total_price, 2) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->status === 'voided')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Void</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Paid</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($order->status !== 'voided')
                                        <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" class="btn btn-sm btn-light text-secondary me-1" title="Print Receipt">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to VOID Order #{{ $order->id }}? This cannot be undone.');">
                                            @csrf
                                            <button class="btn btn-sm btn-light text-danger" title="Void Order"><i class="fas fa-ban"></i></button>
                                        </form>
                                        @endif
                                    @else
                                        <span class="small"><i class="fas fa-times-circle"></i></span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-mug-hot fa-2x mb-3 opacity-25"></i>
                                    <p class="mb-0">No transactions recorded yet today.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- CRITICAL STOCK LIST --}}
        <div class="col-lg-4">
            <div class="card card-custom h-100">
                <div class="table-card-header bg-danger-subtle bg-opacity-10">
                    <h5 class="m-0 fw-bold text-danger"><i class="fas fa-clipboard-list me-2"></i>Critical Stock</h5>
                    <a href="{{ route('ingredients.index') }}" class="btn btn-sm btn-light text-danger border shadow-sm rounded-pill px-3">Manage</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($lowStockIngredients as $ing)
                    <div class="list-group-item p-3 border-light">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="fw-bold text-dark">{{ $ing->name }}</span>
                            <span class="badge bg-danger rounded-pill">{{ $ing->stock }} {{ $ing->unit }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-truck fa-xs me-1"></i> {{ $ing->supplier->name ?? 'No Supplier' }}
                            </small>
                            <small class="text-danger fw-medium" style="font-size:0.75rem;">Below {{ $ing->reorder_level }}</small>
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-danger" style="width: {{ min(100, ($ing->stock / max(1, $ing->reorder_level)) * 50) }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <div class="mb-3 text-success opacity-25">
                            <i class="fas fa-check-circle fa-4x"></i>
                        </div>
                        <h6 class="fw-bold text-success">Inventory Healthy</h6>
                        <p class="text-muted small px-4">All ingredients are above their reorder levels.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart Initialization
    const ctx = document.getElementById('salesChart');
    
    // DATA PASSED FROM CONTROLLER
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
                    borderColor: '#6F4E37', // var(--primary-coffee)
                    backgroundColor: 'rgba(111, 78, 55, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#FFF',
                    pointBorderColor: '#6F4E37',
                    pointBorderWidth: 2,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#3E2723',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    // Currency formatting
                                    label += new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f0f0f0' },
                        ticks: {
                            font: { size: 10 },
                            callback: function(value, index, values) {
                                return '₱' + value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' }, color: '#86868B' }
                    }
                }
            }
        });
    }
</script>
@endsection