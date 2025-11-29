@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark m-0">Order History</h4>
            <p class="text-secondary small m-0">Track all transaction records</p>
        </div>
        
        <div class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-secondary"></i></span>
                <input type="text" id="orderSearch" class="form-control border-start-0 ps-0" placeholder="Search Order ID...">
            </div>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-header bg-white border-bottom px-4 pt-4 pb-0">
            <ul class="nav nav-tabs card-header-tabs" id="orderTabs">
                <li class="nav-item">
                    <a class="nav-link active fw-bold text-dark" href="#" onclick="filterOrders('all', this)">All Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary" href="#" onclick="filterOrders('completed', this)">Completed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary" href="#" onclick="filterOrders('void', this)">Voided/Pending</a>
                </li>
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="ordersTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">Order ID</th>
                            <th class="text-secondary text-uppercase small fw-bold">Date & Time</th> 
                            <th class="text-secondary text-uppercase small fw-bold">Total</th>
                            <th class="text-secondary text-uppercase small fw-bold">Status</th>
                            <th class="text-end pe-4 text-secondary text-uppercase small fw-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="order-row" data-status="{{ $order->status }}">
                            <td class="ps-4 fw-bold font-monospace text-primary-coffee order-id">#{{ $order->id }}</td>
                            
                            <td>
                                <div class="small fw-bold text-dark">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="small text-muted">{{ $order->created_at->format('h:i A') }}</div>
                            </td>

                            <td class="fw-bold">₱{{ number_format($order->total_price, 2) }}</td>

                            <td>
                                @if($order->status == 'completed')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                        <i class="fas fa-check-circle me-1"></i> Completed
                                    </span>
                                @elseif($order->status == 'void_pending')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">
                                        <i class="fas fa-clock me-1"></i> Void Req
                                    </span>
                                @elseif($order->status == 'voided')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">
                                        <i class="fas fa-ban me-1"></i> Voided
                                    </span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                {{-- View Receipt --}}
                                <a href="{{ route('orders.receipt', $order->id) }}" class="btn btn-sm btn-light text-secondary me-1 border shadow-sm" target="_blank" title="Print Receipt">
                                    <i class="fas fa-print"></i>
                                </a>

                                {{-- VOID ACTIONS --}}
                                @if($order->status == 'completed')
                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light text-danger border" onclick="return confirm('VOID Order #{{ $order->id }}?')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('orders.requestVoid', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light text-warning border" onclick="return confirm('Request Void #{{ $order->id }}?')">
                                                <i class="fas fa-flag"></i>
                                            </button>
                                        </form>
                                    @endif
                                @elseif($order->status == 'void_pending' && auth()->user()->role === 'admin')
                                    <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Approve Void?')">
                                            Approve
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-receipt fa-2x mb-3 opacity-25"></i>
                                <p class="mb-0">No orders found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-3 border-top">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    // 1. FILTER BY TAB
    function filterOrders(type, element) {
        // Update Active Tab UI
        document.querySelectorAll('.nav-link').forEach(el => {
            el.classList.remove('active', 'fw-bold', 'text-dark');
            el.classList.add('text-secondary');
        });
        element.classList.add('active', 'fw-bold', 'text-dark');
        element.classList.remove('text-secondary');

        // Filter Rows
        const rows = document.querySelectorAll('.order-row');
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            if (type === 'all') {
                row.style.display = '';
            } else if (type === 'completed') {
                row.style.display = status === 'completed' ? '' : 'none';
            } else if (type === 'void') {
                row.style.display = (status === 'voided' || status === 'void_pending') ? '' : 'none';
            }
        });
    }

    // 2. SEARCH BY ID
    document.getElementById('orderSearch').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        const rows = document.querySelectorAll('.order-row');
        
        rows.forEach(row => {
            const id = row.querySelector('.order-id').innerText.toLowerCase();
            if(id.includes(val)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection