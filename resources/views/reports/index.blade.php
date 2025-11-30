@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Header & Date Filter --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark m-0"><i class="fas fa-chart-bar me-2 text-primary-coffee"></i>Sales Reports</h4>
            <p class="text-secondary small m-0">Analyze performance and export data.</p>
        </div>
        
        <form action="{{ route('reports.index') }}" method="GET" class="d-flex gap-2 align-items-center bg-white p-2 rounded-4 shadow-sm border">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-0"><i class="fas fa-calendar"></i></span>
                <input type="date" name="start_date" class="form-control border-0 bg-light" value="{{ $startDate }}" required>
            </div>
            <span class="text-muted">-</span>
            <div class="input-group input-group-sm">
                <input type="date" name="end_date" class="form-control border-0 bg-light" value="{{ $endDate }}" required>
            </div>
            <button type="submit" class="btn btn-sm btn-primary-coffee rounded-3 px-3">Filter</button>
        </form>
    </div>

    {{-- Export Buttons --}}
    <div class="d-flex justify-content-end mb-4 gap-2">
        <a href="{{ route('reports.export', ['type' => 'csv', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-csv me-1"></i> Export CSV
        </a>
        <a href="{{ route('reports.export', ['type' => 'pdf', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </a>
    </div>

    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="text-secondary small fw-bold text-uppercase">Total Revenue</div>
                <h3 class="fw-bold text-dark mb-0">₱{{ number_format($totalSales, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="text-secondary small fw-bold text-uppercase">Total Orders</div>
                <h3 class="fw-bold text-dark mb-0">{{ $totalOrders }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="text-secondary small fw-bold text-uppercase">Avg. Order Value</div>
                <h3 class="fw-bold text-dark mb-0">₱{{ number_format($averageOrderValue, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="text-secondary small fw-bold text-uppercase">Cash vs Digital</div>
                <div class="d-flex justify-content-between mt-2">
                    <span class="badge bg-success bg-opacity-10 text-success">Cash: ₱{{ number_format($cashSales, 0) }}</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary">Digi: ₱{{ number_format($digitalSales, 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Best Sellers --}}
        <div class="col-lg-5">
            <div class="card card-custom h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="m-0 fw-bold"><i class="fas fa-crown text-warning me-2"></i>Top Performing Products</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Product</th>
                                <th class="text-center">Sold</th>
                                <th class="text-end pe-3">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestSellers as $item)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold">{{ $item->product->name }}</div>
                                    <small class="text-muted">₱{{ number_format($item->product->price, 2) }}</small>
                                </td>
                                <td class="text-center fw-bold">{{ $item->total_qty }}</td>
                                <td class="text-end pe-3 text-success">₱{{ number_format($item->total_revenue, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted">No sales in this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Transaction History --}}
        <div class="col-lg-7">
            <div class="card card-custom h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="m-0 fw-bold"><i class="fas fa-list me-2"></i>Transaction Log</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Date</th>
                                <th>Payment</th>
                                <th class="text-end pe-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportOrders as $order)
                            <tr>
                                <td class="ps-3"><span class="font-monospace text-primary">#{{ $order->id }}</span></td>
                                <td class="small">{{ $order->created_at->format('M d, h:i A') }}</td>
                                <td>
                                    <span class="badge {{ $order->payment_mode == 'cash' ? 'bg-secondary' : 'bg-info' }}">
                                        {{ strtoupper($order->payment_mode) }}
                                    </span>
                                </td>
                                <td class="text-end pe-3 fw-bold">₱{{ number_format($order->total_price, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">No transactions found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection