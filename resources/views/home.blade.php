<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Kape Ni Asero</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            /* Consistent Palette */
            --primary-coffee: #6F4E37;
            --dark-coffee: #3E2723;
            --accent-gold: #8B7355;
            --surface-cream: #FFF8E7;
            --surface-white: #FFFFFF;
            --text-dark: #2C1810;
            --text-light: #FFF8E7;
            --success-green: #689F38;
            --border-light: #F0E5D0;
            --input-border: #E8DCC8;
            --color-danger: #D84315;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-coffee) 0%, var(--dark-coffee) 100%);
            color: var(--text-dark);
            min-height: 100vh;
            padding-bottom: 2rem;
        }

        /* HEADER styling */
        .dashboard-header {
            background-color: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 16px;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-coffee) !important;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        /* BUTTONS */
        .btn-glass {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: var(--primary-coffee);
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
            color: var(--dark-coffee);
        }

        .btn-primary {
            background-color: var(--primary-coffee);
            border-color: var(--primary-coffee);
            color: white;
        }
        .btn-primary:hover {
            background-color: #5A3D2B;
            border-color: #5A3D2B;
        }

        .btn-outline-dark {
            border-color: var(--primary-coffee);
            color: var(--primary-coffee);
        }
        .btn-outline-dark:hover {
            background-color: var(--primary-coffee);
            color: white;
        }

        /* CARDS */
        .card {
            border: none;
            border-radius: 20px;
            background: var(--surface-white);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            overflow: hidden;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: var(--surface-cream);
            border-bottom: 1px solid var(--border-light);
            color: var(--primary-coffee);
            font-weight: 700;
            padding: 1.25rem 1.5rem;
        }

        /* KPI CARDS */
        .kpi-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* TABLE STYLING */
        .table thead th {
            background-color: var(--surface-cream);
            color: var(--primary-coffee);
            font-weight: 600;
            border-bottom: 2px solid var(--border-light);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        
        .badge-status {
            padding: 0.5em 0.8em;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container mt-4 pb-5">
    
    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-lg border-0" style="background: #D1E7DD; color: #0F5132;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-lg border-0" style="background: #F8D7DA; color: #842029;">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- HEADER -->
    <div class="dashboard-header">
        <div class="d-flex align-items-center">
            <div class="bg-white p-2 rounded-circle me-3 shadow-sm border border-light">
                <img src="{{ asset('ka.png') }}" alt="Logo" style="height: 45px;"> 
            </div>
            <div>
                <h4 class="fw-bold m-0 text-dark">Admin Dashboard</h4>
                <div class="small text-muted">
                    Welcome, <strong style="color: var(--primary-coffee);">{{ Auth::user()->name }}</strong> 
                    <span class="badge bg-warning text-dark ms-1">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            @if(Auth::user()->role == 'admin')
                <div class="btn-group shadow-sm">
                    <a href="{{ route('ingredients.index') }}" class="btn btn-light border text-dark btn-sm fw-medium"><i class="fas fa-boxes text-muted me-1"></i> Stock</a>
                    <a href="{{ route('categories.index') }}" class="btn btn-light border text-dark btn-sm fw-medium"><i class="fas fa-tags text-muted me-1"></i> Cats</a>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-light border text-dark btn-sm fw-medium"><i class="fas fa-truck text-muted me-1"></i> Suppliers</a>
                </div>
                <a href="{{ route('products.create') }}" class="btn btn-outline-light text-dark bg-white fw-bold btn-sm shadow-sm"><i class="fas fa-plus text-success"></i> New Item</a>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm fw-bold shadow-sm"><i class="fas fa-cash-register"></i> Open POS</a>
            @endif
            
            <a href="{{ route('logout') }}" class="btn btn-danger btn-sm shadow-sm d-flex align-items-center justify-content-center" style="width: 34px;"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
               <i class="fas fa-power-off"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>

    <!-- KPI CARDS -->
    <div class="row mb-4 g-4">
        <!-- Sales -->
        <div class="col-md-4">
            <div class="card h-100 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Total Sales</p>
                        <h2 class="fw-bold mb-0" style="color: var(--success-green);">₱{{ number_format($todaySales, 2) }}</h2>
                    </div>
                    <div class="kpi-icon-wrapper" style="background: rgba(104, 159, 56, 0.1); color: var(--success-green);">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="col-md-4">
            <div class="card h-100 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Orders Today</p>
                        <h2 class="fw-bold mb-0" style="color: var(--primary-coffee);">{{ $todayOrders }}</h2>
                    </div>
                    <div class="kpi-icon-wrapper" style="background: rgba(111, 78, 55, 0.1); color: var(--primary-coffee);">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <div class="col-md-4">
            <div class="card h-100 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Low Stock Alerts</p>
                        <h2 class="fw-bold mb-0" style="color: var(--color-danger);">{{ $lowStockIngredients->count() }}</h2>
                    </div>
                    <div class="kpi-icon-wrapper" style="background: rgba(216, 67, 21, 0.1); color: var(--color-danger);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="row g-4">
        
        <!-- Transaction Table -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold"><i class="fas fa-history me-2"></i>Recent Transactions</h5>
                    <span class="badge bg-white text-dark border shadow-sm">Today</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Cashier</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr class="{{ $order->status === 'voided' ? 'opacity-50 bg-light' : '' }}">
                                <td class="ps-4 fw-bold text-secondary">#{{ $order->id }}</td>
                                <td class="text-dark">{{ $order->user->name }}</td>
                                <td>
                                    @if($order->status === 'voided')
                                        <span class="text-decoration-line-through text-muted">₱{{ number_format($order->total_price, 2) }}</span>
                                    @else
                                        <span class="fw-bold" style="color: var(--success-green);">₱{{ number_format($order->total_price, 2) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->status === 'voided')
                                        <span class="badge bg-danger badge-status">VOID</span>
                                    @else
                                        <span class="badge bg-success badge-status" style="background-color: var(--success-green) !important;">PAID</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($order->status !== 'voided')
                                        <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" class="btn btn-sm btn-light border text-muted" title="Receipt"><i class="fas fa-print"></i></a>
                                        <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirm VOID for Order #{{ $order->id }}?');">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger border-0" title="Void"><i class="fas fa-ban"></i></button>
                                        </form>
                                    @else
                                        <small class="text-muted fst-italic">Cancelled</small>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No sales yet today.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stock Alerts List -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-danger"><i class="fas fa-box-open me-2"></i>Critical Stock</h5>
                    <a href="{{ route('ingredients.index') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm bg-white">Fix</a>
                </div>
                <ul class="list-group list-group-flush rounded-bottom">
                    @forelse($lowStockIngredients as $ing)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <span class="fw-bold text-dark d-block">{{ $ing->name }}</span>
                            @if($ing->supplier)
                                <small class="text-muted" style="font-size: 0.8em;"><i class="fas fa-truck fa-xs me-1"></i> {{ $ing->supplier->name }}</small>
                            @else
                                <small class="text-muted fst-italic" style="font-size: 0.8em;">No supplier linked</small>
                            @endif
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger rounded-pill mb-1">{{ $ing->stock }} {{ $ing->unit }}</span>
                            <br>
                            <small class="text-muted" style="font-size: 0.75rem;">Min: {{ $ing->reorder_level }}</small>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-5 border-0">
                        <i class="fas fa-check-circle fa-4x mb-3 opacity-25" style="color: var(--success-green);"></i><br>
                        <h6 class="fw-bold" style="color: var(--success-green);">All Good!</h6>
                        <p class="small mb-0">Inventory levels are healthy.</p>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

    <!-- AUDIT LOG -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0 fw-bold"><i class="fas fa-shield-alt me-2"></i>Audit Log</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0 small">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">User</th>
                                <th class="py-3">Action</th>
                                <th class="py-3">Details</th>
                                <th class="py-3 text-end pe-4">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $logs = \App\Models\ActivityLog::with('user')->latest()->take(5)->get();
                            @endphp

                            @forelse($logs as $log)
                            <tr>
                                <td class="ps-4 fw-bold" style="color: var(--primary-coffee);">{{ $log->user->name ?? 'System' }}</td>
                                <td>
                                    @if(str_contains($log->action, 'Void'))
                                        <span class="badge bg-danger text-white shadow-sm px-2 py-1 rounded">{{ $log->action }}</span>
                                    @elseif(str_contains($log->action, 'Stock'))
                                        <span class="badge text-dark bg-warning shadow-sm px-2 py-1 rounded">{{ $log->action }}</span>
                                    @else
                                        <span class="badge bg-secondary shadow-sm px-2 py-1 rounded">{{ $log->action }}</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $log->details }}</td>
                                <td class="text-end pe-4 text-secondary">{{ $log->created_at->diffForHumans() }}</td>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>