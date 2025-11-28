<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Kape Ni Asero</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* BRAND PALETTE */
            --primary-coffee: #6F4E37;
            --primary-coffee-hover: #5A3D2B;
            --dark-coffee: #3E2723;
            --accent-gold: #C5A065;
            --surface-cream: #FFF8E7;
            --surface-glass: rgba(255, 255, 255, 0.92);
            --text-dark: #2C1810;
            --text-secondary: #6D5E57;
            --success-green: #558B2F;
            --danger-red: #D32F2F;
            --border-light: #EFEBE9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F5F5F5 0%, #E0E0E0 100%);
            background-image: radial-gradient(at 0% 0%, rgba(111, 78, 55, 0.05) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(197, 160, 101, 0.1) 0px, transparent 50%);
            color: var(--text-dark);
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        /* --- PREMIUM NAVBAR STYLING --- */
        .navbar-premium {
            background-color: var(--surface-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 24px -1px rgba(62, 39, 35, 0.06);
            padding: 0.8rem 1rem;
            margin-bottom: 2.5rem;
            border-radius: 24px;
            margin-top: 1rem;
        }

        .navbar-brand-wrapper {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-container {
            background: white;
            padding: 6px;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .navbar-brand:hover .logo-container {
            transform: scale(1.05) rotate(-3deg);
        }

        .brand-text {
            line-height: 1.1;
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--text-dark);
            letter-spacing: -0.02em;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Nav Pills & Buttons */
        .nav-pill-custom {
            border-radius: 12px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-secondary);
            transition: all 0.2s ease;
            border: 1px solid transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .nav-pill-custom:hover {
            background-color: rgba(111, 78, 55, 0.08);
            color: var(--primary-coffee);
            transform: translateY(-1px);
        }

        .nav-pill-custom.active {
            background-color: var(--primary-coffee);
            color: white;
            box-shadow: 0 4px 12px rgba(111, 78, 55, 0.25);
        }

        .btn-action {
            border-radius: 12px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-create {
            background: white;
            border: 1px solid var(--border-light);
            color: var(--text-dark);
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .btn-create:hover {
            border-color: var(--success-green);
            color: var(--success-green);
            background: #F1F8E9;
        }

        .btn-pos {
            background: linear-gradient(135deg, var(--primary-coffee) 0%, var(--dark-coffee) 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.3);
        }
        .btn-pos:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(111, 78, 55, 0.4);
            color: white;
        }

        /* Mobile Toggler */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            border-radius: 10px;
            color: var(--primary-coffee);
            background-color: rgba(111, 78, 55, 0.05);
            transition: background 0.2s;
        }
        .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
            background-color: rgba(111, 78, 55, 0.1);
        }

        /* --- CARDS & WIDGETS --- */
        .card-custom {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
            overflow: hidden;
        }
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px -10px rgba(0,0,0,0.12);
        }

        .kpi-card {
            position: relative;
            overflow: hidden;
        }
        .kpi-bg-icon {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 5rem;
            opacity: 0.05;
            transform: rotate(-15deg);
        }

        .table-card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-light);
            padding: 1.5rem;
        }

        /* TABLE */
        .table > :not(caption) > * > * {
            padding: 1rem 1rem;
            background-color: transparent;
            border-bottom-color: var(--border-light);
        }
        .table thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            font-weight: 600;
        }
        
        /* ALERTS */
        .alert-floating {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>

<div class="container">
    
    <nav class="navbar navbar-expand-lg navbar-premium">
        <div class="container-fluid px-1">
            <a class="navbar-brand p-0" href="#">
                <div class="navbar-brand-wrapper">
                    <div class="logo-container">
                        <img src="{{ asset('ka.png') }}" alt="Logo" style="height: 38px; width: auto;">
                    </div>
                    <div class="brand-info">
                        <div class="brand-title">KAPE NI ASERO</div>
                        <div class="brand-subtitle">
                            Admin Dashboard <span class="mx-1">•</span> 
                            <span class="text-primary-coffee fw-bold">{{ Auth::user()->name }}</span>
                        </div>
                    </div>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars fs-5"></i>
            </button>

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="mainNav">
                <div class="ms-auto d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 gap-lg-3">
                    
                    @if(Auth::user()->role == 'admin')
                        <div class="d-flex flex-column flex-lg-row gap-1 bg-light p-1 rounded-4 border border-light">
                            <a href="{{ route('ingredients.index') }}" class="nav-pill-custom">
                                <i class="fas fa-boxes fa-sm"></i> Stock
                            </a>
                            <a href="{{ route('categories.index') }}" class="nav-pill-custom">
                                <i class="fas fa-tags fa-sm"></i> Categories
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="nav-pill-custom">
                                <i class="fas fa-truck fa-sm"></i> Suppliers
                            </a>
                        </div>

                        <div class="vr d-none d-lg-block mx-1 opacity-25"></div>

                        <a href="{{ route('products.create') }}" class="btn btn-action btn-create">
                            <i class="fas fa-plus-circle text-success"></i> New Item
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-action btn-pos">
                            <i class="fas fa-cash-register"></i> POS System
                        </a>
                    @endif

                    <div class="d-lg-none mt-2 border-top pt-2"></div>
                    <a href="{{ route('logout') }}" 
                       class="btn btn-action btn-light text-danger border-0 d-flex justify-content-center" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       title="Sign Out">
                        <span class="d-lg-none">Sign Out</span>
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-floating d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle fs-4 me-3 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show alert-floating d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-circle fs-4 me-3 text-danger"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-5 g-4">
        <div class="col-md-4">
            <div class="card card-custom kpi-card h-100 p-4">
                <i class="fas fa-coins kpi-bg-icon text-warning"></i>
                <div class="d-flex flex-column h-100 position-relative">
                    <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Total Sales Today</span>
                    <h2 class="display-6 fw-bold mb-0 text-dark">₱{{ number_format($todaySales, 2) }}</h2>
                    <div class="mt-auto pt-3 text-success small fw-medium">
                        <i class="fas fa-arrow-up me-1"></i> Keep it brewing!
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom kpi-card h-100 p-4">
                <i class="fas fa-receipt kpi-bg-icon text-primary"></i>
                <div class="d-flex flex-column h-100 position-relative">
                    <span class="text-uppercase text-secondary fw-bold small tracking-wide mb-2">Transactions</span>
                    <h2 class="display-6 fw-bold mb-0 text-dark">{{ $todayOrders }}</h2>
                    <div class="mt-auto pt-3 text-primary-coffee small fw-medium">
                        <i class="fas fa-coffee me-1"></i> Orders served
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom kpi-card h-100 p-4" style="border-bottom: 4px solid var(--danger-red);">
                <i class="fas fa-exclamation-triangle kpi-bg-icon text-danger"></i>
                <div class="d-flex flex-column h-100 position-relative">
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
        
        <div class="col-lg-8">
            <div class="card card-custom h-100">
                <div class="table-card-header d-flex justify-content-between align-items-center">
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

        <div class="col-lg-4">
            <div class="card card-custom h-100">
                <div class="table-card-header d-flex justify-content-between align-items-center bg-danger-subtle bg-opacity-10">
                    <h5 class="m-0 fw-bold text-danger"><i class="fas fa-clipboard-list me-2"></i>Critical Stock</h5>
                    <a href="{{ route('ingredients.index') }}" class="btn btn-sm btn-white text-danger border shadow-sm rounded-pill px-3">Manage</a>
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
                                            {{ str_contains($log->action, 'Void') ? 'bg-danger-subtle text-danger' : 
                                               (str_contains($log->action, 'Stock') ? 'bg-warning-subtle text-dark' : 'bg-light text-secondary border') }}">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>