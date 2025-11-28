<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History | Kape Ni Asero</title>
    
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

        /* --- PREMIUM NAVBAR STYLING (Matched to Dashboard) --- */
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

        /* BUTTONS */
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

        /* CARD & TABLE */
        .card-custom {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .table-card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-light);
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

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
            background-color: #FAFAFA;
        }
    </style>
</head>
<body>

<div class="container">
    
    <nav class="navbar navbar-expand-lg navbar-premium">
        <div class="container-fluid px-1">
            <a class="navbar-brand p-0" href="{{ route('home') }}">
                <div class="navbar-brand-wrapper">
                    <div class="logo-container">
                        <img src="{{ asset('ka.png') }}" alt="Logo" style="height: 38px; width: auto;">
                    </div>
                    <div class="brand-info">
                        <div class="brand-title">KAPE NI ASERO</div>
                        <div class="brand-subtitle">
                            Order History <span class="mx-1">•</span> 
                            <span class="text-primary-coffee fw-bold">{{ Auth::user()->name }}</span>
                        </div>
                    </div>
                </div>
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="{{ route('products.index') }}" class="btn btn-action btn-pos">
                    <i class="fas fa-cash-register"></i> <span class="d-none d-md-inline">Back to POS</span>
                </a>
                
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('home') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:40px;height:40px;" title="Dashboard">
                        <i class="fas fa-th-large text-secondary"></i>
                    </a>
                @endif
                
                <a href="{{ route('logout') }}" 
                   class="btn btn-light text-danger rounded-circle shadow-sm d-flex align-items-center justify-content-center" 
                   style="width:40px;height:40px;"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   title="Sign Out">
                    <i class="fas fa-power-off"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-custom">
        <div class="table-card-header">
            <h5 class="m-0 fw-bold text-dark"><i class="fas fa-history me-2 text-primary-coffee"></i> Transaction History</h5>
            <span class="badge bg-light text-dark border">Total Orders: {{ $orders->total() }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Order ID</th>
                        <th>Date & Time</th>
                        <th>Cashier</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="{{ $order->status === 'voided' ? 'opacity-50' : '' }}">
                        <td class="ps-4 fw-bold font-monospace">#{{ $order->id }}</td>
                        <td>
                            <div class="small fw-bold">{{ $order->created_at->format('M d, Y') }}</div>
                            <div class="small text-muted">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td>{{ $order->user->name ?? 'Unknown' }}</td>
                        <td>₱{{ number_format($order->total_price, 2) }}</td>
                        <td>
                            @if($order->status == 'completed')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Completed</span>
                            @elseif($order->status == 'void_pending')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">Void Requested</span>
                            @elseif($order->status == 'voided')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Voided</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            {{-- View Receipt --}}
                            <a href="{{ route('orders.receipt', $order->id) }}" class="btn btn-sm btn-light text-secondary me-1" target="_blank" title="Receipt">
                                <i class="fas fa-print"></i>
                            </a>

                            {{-- VOID LOGIC --}}
                            @if($order->status == 'completed')
                                @if(auth()->user()->role === 'admin')
                                    {{-- ADMIN: Direct Void --}}
                                    <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light text-danger border" onclick="return confirm('Are you sure you want to VOID Order #{{ $order->id }}?')">
                                            <i class="fas fa-ban me-1"></i> Void
                                        </button>
                                    </form>
                                @else
                                    {{-- EMPLOYEE: Request Void --}}
                                    <form action="{{ route('orders.requestVoid', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light text-warning border" onclick="return confirm('Request a void for Order #{{ $order->id }}?')">
                                            <i class="fas fa-flag me-1"></i> Request Void
                                        </button>
                                    </form>
                                @endif

                            @elseif($order->status == 'void_pending')
                                @if(auth()->user()->role === 'admin')
                                    {{-- ADMIN: Approve Void --}}
                                    <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Approve void request for Order #{{ $order->id }}?')">
                                            <i class="fas fa-check me-1"></i> Approve Void
                                        </button>
                                    </form>
                                @else
                                    {{-- EMPLOYEE: Pending Status --}}
                                    <button class="btn btn-sm btn-light text-muted border" disabled>
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </button>
                                @endif
                            @else
                                <button class="btn btn-sm btn-light border-0" disabled>
                                    <i class="fas fa-times-circle text-muted"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-receipt fa-2x mb-3 opacity-25"></i>
                            <p class="mb-0">No orders found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center py-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>