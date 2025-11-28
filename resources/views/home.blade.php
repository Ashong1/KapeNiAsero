<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Shared Styles from app.blade.php for consistency */
        :root {
            --color-mocha: #3A241D;
            --color-sienna: #B48C6B;
            --color-cream: #F9F4F0;
            --color-olive: #689F38;
            --color-danger: #D84315;
        }
        body { background-color: #f8f9fa; }
        .text-mocha { color: var(--color-mocha); }
        .bg-mocha { background-color: var(--color-mocha); }
        .btn-primary { background-color: var(--color-sienna); border-color: var(--color-sienna); color: var(--color-mocha); }
        .btn-primary:hover { background-color: #A06F51; border-color: #A06F51; color: white; }
    </style>
</head>
<body>

<div class="container mt-4 pb-5">
    
    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 py-3 px-4 bg-white shadow-sm rounded-3 border-start border-5 border-primary">
        <div class="d-flex align-items-center">
            <h4 class="fw-bold m-0 text-mocha me-3">
                <img src="{{ asset('ka.png') }}" alt="Logo" style="height: 40px;" class="me-2"> 
                Kape Ni Asero
            </h4>   
            <div class="border-start ps-3 text-muted d-none d-md-block">
                <small>User: <strong class="text-dark">{{ Auth::user()->name }}</strong></small>
                <span class="badge bg-secondary ms-1">{{ Auth::user()->role }}</span>
            </div>
        </div>
        <div>
            @if(Auth::user()->role == 'admin')
                <div class="btn-group shadow-sm me-2">
                    <a href="{{ route('ingredients.index') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-boxes me-1"></i> Warehouse
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-tags me-1"></i> Categories
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-truck me-1"></i> Suppliers
                    </a>
                </div>

                <a href="{{ route('products.create') }}" class="btn btn-outline-primary btn-sm me-2 fw-bold">
                    <i class="fas fa-plus"></i> Add Item
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm me-2 fw-bold shadow-sm">
                    <i class="fas fa-cash-register"></i> POS System
                </a>
            @endif
            
            <a href="{{ route('logout') }}" class="btn btn-danger btn-sm shadow-sm"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
               <i class="fas fa-sign-out-alt"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>

    <!-- KPI CARDS -->
    <div class="row mb-4 g-3">
        <!-- Sales Card -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 border-start border-success border-5 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold small mb-1">Sales Today</h6>
                            <h3 class="fw-bold text-success mb-0">₱{{ number_format($todaySales, 2) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-coins fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 border-start border-primary border-5 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold small mb-1">Orders Today</h6>
                            <h3 class="fw-bold text-primary mb-0">{{ $todayOrders }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-receipt fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Card -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 border-start border-danger border-5 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold small mb-1">Restock Needed</h6>
                            <h3 class="fw-bold text-danger mb-0">{{ $lowStockIngredients->count() }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT ROW -->
    <div class="row g-4">
        
        <!-- Transaction History -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-mocha"><i class="fas fa-history me-2"></i>Recent Transactions</h5>
                    <span class="badge bg-light text-dark border">Today's Activity</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr class="text-uppercase small text-muted">
                                <th>Order #</th>
                                <th>Cashier</th>
                                <th>Total</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr class="{{ $order->status === 'voided' ? 'table-secondary text-muted' : '' }}">
                                <td class="fw-bold text-secondary">#{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    @if($order->status === 'voided')
                                        <span class="text-decoration-line-through">₱{{ number_format($order->total_price, 2) }}</span>
                                    @else
                                        <span class="fw-bold text-success">₱{{ number_format($order->total_price, 2) }}</span>
                                    @endif
                                </td>
                                <td class="small">{{ $order->created_at->format('h:i A') }}</td>
                                <td>
                                    @if($order->status === 'voided')
                                        <span class="badge bg-danger">VOIDED</span>
                                    @else
                                        <span class="badge bg-success">COMPLETED</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($order->status !== 'voided')
                                        <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" class="btn btn-sm btn-light border" title="Print Receipt">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        
                                        <!-- VOID BUTTON -->
                                        <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('⚠️ WARNING: VOID ORDER #{{ $order->id }}?\n\nThis will cancel the sale and RETURN items to inventory.\n\nAre you sure?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Void & Refund">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="small text-muted fst-italic">No actions</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-cash-register fa-3x mb-3 opacity-25"></i><br>
                                    No sales transactions yet today.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-danger"><i class="fas fa-box-open me-2"></i>Restock Needed</h5>
                    <a href="{{ route('ingredients.index') }}" class="btn btn-sm btn-danger px-3 rounded-pill">Manage</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($lowStockIngredients as $ing)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="fw-bold text-dark d-block">{{ $ing->name }}</span>
                                @if($ing->supplier)
                                    <small class="text-muted"><i class="fas fa-truck fa-xs me-1"></i> {{ $ing->supplier->name }}</small>
                                @else
                                    <small class="text-muted fst-italic">No supplier linked</small>
                                @endif
                            </div>
                            <div class="text-end">
                                <span class="badge bg-danger rounded-pill mb-1">{{ $ing->stock }} {{ $ing->unit }}</span>
                                <br>
                                <small class="text-muted" style="font-size: 0.7rem;">Target: {{ $ing->reorder_level }}</small>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted py-5 border-0">
                            <i class="fas fa-check-circle text-success fa-4x mb-3 opacity-25"></i><br>
                            <h6 class="fw-bold text-success">All Good!</h6>
                            <p class="small mb-0">Inventory levels are healthy.</p>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div> <!-- End Row -->

    <!-- NEW SECTION: AUDIT LOG -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="m-0 fw-bold text-dark"><i class="fas fa-clipboard-list me-2"></i>System Activity Log</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 small">
                            <thead class="bg-light">
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Details</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Direct Data Fetch for Dashboard (Quick Implementation) -->
                                @php
                                    $logs = \App\Models\ActivityLog::with('user')->latest()->take(10)->get();
                                @endphp

                                @forelse($logs as $log)
                                <tr>
                                    <td class="fw-bold">{{ $log->user->name ?? 'System' }}</td>
                                    <td>
                                        @if(str_contains($log->action, 'Void'))
                                            <span class="badge bg-danger">{{ $log->action }}</span>
                                        @elseif(str_contains($log->action, 'Stock'))
                                            <span class="badge bg-warning text-dark">{{ $log->action }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $log->action }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $log->details }}</td>
                                    <td class="text-secondary">{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">No activity recorded yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END AUDIT LOG -->

</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>