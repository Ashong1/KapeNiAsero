@extends('layouts.app')

@section('styles')
<style>
    /* Dashboard Specific Styles */
    .kpi-card {
        position: relative;
        overflow: hidden;
        border: none;
        border-radius: 20px;
        background: white;
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px -10px rgba(0,0,0,0.12);
    }
    
    .kpi-bg-icon {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.05;
        transform: rotate(-15deg);
        z-index: 0;
    }

    .card-content-wrapper {
        position: relative;
        z-index: 1;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .table-card-header {
        background: transparent;
        border-bottom: 1px solid var(--border-light);
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    /* Audit Log Badge Styles */
    .badge-soft-void { background-color: #FEF2F2; color: #DC2626; }
    .badge-soft-stock { background-color: #FFFBEB; color: #D97706; }
    .badge-soft-system { background-color: #F3F4F6; color: #4B5563; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- The Navbar is now handled by layouts.app --}}
    
    {{-- KPI CARDS ROW --}}
    <div class="row mb-5 g-4">
        <div class="col-md-4">
            <div class="card kpi-card h-100 p-4">
                <i class="fas fa-coins kpi-bg-icon text-warning"></i>
                <div class="card-content-wrapper">
                    <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Total Sales Today</span>
                    <h2 class="display-6 fw-bold mb-0 text-dark">₱{{ number_format($todaySales, 2) }}</h2>
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
                    <h2 class="display-6 fw-bold mb-0 text-dark">{{ $todayOrders }}</h2>
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
                                        <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to VOID Order #{{ $order->id }}? This cannot be undone.');">
                                            @csrf
                                            <button class="btn btn-sm btn-light text-danger" title="Void Order"><i class="fas fa-ban"></i></button>
                                        </form>
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
                        <p class="text-muted small px-4">All ingredients are above their reorder levels. Great job!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- SYSTEM AUDIT LOG --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h6 class="text-uppercase text-secondary fw-bold small letter-spacing-wide">System Audit Log</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">User</th>
                                    <th>Activity</th>
                                    <th>Details</th>
                                    <th class="text-end pe-4">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $logs = \App\Models\ActivityLog::with('user')->latest()->take(5)->get();
                                @endphp
                                @forelse($logs as $log)
                                <tr>
                                    <td class="ps-4 fw-medium text-primary-coffee">{{ $log->user->name ?? 'System' }}</td>
                                    <td>
                                        <span class="badge rounded-1 fw-normal 
                                            {{ str_contains($log->action, 'Void') ? 'badge-soft-void' : 
                                               (str_contains($log->action, 'Stock') ? 'badge-soft-stock' : 'badge-soft-system') }}">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="text-secondary small">{{ $log->details }}</td>
                                    <td class="text-end pe-4 text-muted small">{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No logs found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection