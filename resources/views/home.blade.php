<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <!-- HEADER (Updated with Navigation Buttons) -->
    <div class="d-flex justify-content-between align-items-center mb-4 py-2 px-3 bg-white shadow-sm rounded">
        <div class="d-flex align-items-center">
            <h4 class="fw-bold m-0 text-primary me-3">
                <img src="{{ asset('ka.png') }}" alt="Logo" style="height: 35px; filter: invert(1);" class="me-2"> 
                Kape Ni Asero
            </h4>   
            <div class="border-start ps-3 text-muted">
                <small>User: <strong class="text-dark">{{ Auth::user()->name }}</strong></small>
                <span class="badge bg-info text-dark ms-1">{{ Auth::user()->role }}</span>
            </div>
        </div>
        <div>
            <!-- SECURITY: Only Admin sees Management Buttons -->
            <!-- SECURITY: Only Admin sees Management Buttons -->
        @if(Auth::user()->role == 'admin')
            
            <!-- EXISTING BUTTONS -->
            <a href="{{ route('ingredients.index') }}" class="btn btn-outline-dark btn-sm me-2">
                <i class="fas fa-boxes"></i> Warehouse
            </a>

            <!-- NEW BUTTONS (ADD THESE) -->
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                <i class="fas fa-tags"></i> Categories
            </a>
            
            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                <i class="fas fa-truck"></i> Suppliers
            </a>
            <!-- END NEW BUTTONS -->

            <a href="{{ route('products.create') }}" class="btn btn-outline-primary btn-sm me-2">
                <i class="fas fa-plus"></i> Add Item
            </a>
            
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm me-2">
                <i class="fas fa-cash-register"></i> POS
            </a>
        @endif
            
            <!-- Logout Button -->
            <a href="{{ route('logout') }}" class="btn btn-danger btn-sm"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <!-- Sales Card -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 border-start border-success border-5 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase mb-1">Sales Today</h6>
                            <h3 class="fw-bold text-success mb-0">₱{{ number_format($todaySales, 2) }}</h3>
                        </div>
                        <i class="fas fa-coins fa-2x text-success opacity-50"></i>
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
                            <h6 class="text-muted text-uppercase mb-1">Orders Today</h6>
                            <h3 class="fw-bold text-primary mb-0">{{ $todayOrders }}</h3>
                        </div>
                        <i class="fas fa-receipt fa-2x text-primary opacity-50"></i>
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
                            <h6 class="text-muted text-uppercase mb-1">Low Stock Items</h6>
                            <h3 class="fw-bold text-danger mb-0">{{ $lowStockIngredients->count() }}</h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders Table -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 fw-bold"><i class="fas fa-history me-2"></i>Recent Transactions</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Cashier</th>
                                <th>Total</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td class="fw-bold text-success">₱{{ number_format($order->total_price, 2) }}</td>
                                <td class="text-muted small">{{ $order->created_at->format('h:i A') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-coffee fa-3x mb-3 opacity-25"></i><br>
                                    No sales yet today.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Low Stock List -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-danger"><i class="fas fa-box-open me-2"></i>Restock Needed</h5>
                    <a href="{{ route('ingredients.index') }}" class="btn btn-sm btn-outline-danger">Manage</a>
                </div>
                <ul class="list-group list-group-flush overflow-auto" style="max-height: 300px;">
                    @forelse($lowStockIngredients as $ing)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark">{{ $ing->name }}</span>
                        <span class="badge bg-danger rounded-pill">{{ $ing->stock }} {{ $ing->unit }}</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-5">
                        <i class="fas fa-check-circle text-success fa-3x mb-3 opacity-25"></i><br>
                        Inventory is healthy.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

</div>

</body>
</html>