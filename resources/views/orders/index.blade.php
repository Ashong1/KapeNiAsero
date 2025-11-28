@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order History</h2>
        {{-- Back to POS Button for Employees --}}
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="bi bi-cart"></i> Back to POS
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            {{-- Alerts for Success/Error messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Date & Time</th>
                            <th>Cashier</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            <td>{{ $order->user->name ?? 'Unknown' }}</td>
                            <td>₱{{ number_format($order->total_price, 2) }}</td>
                            <td>
                                @if($order->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($order->status == 'void_pending')
                                    <span class="badge bg-warning text-dark">Void Requested</span>
                                @elseif($order->status == 'voided')
                                    <span class="badge bg-danger">Voided</span>
                                @endif
                            </td>
                            <td class="text-end">
                                {{-- View Receipt --}}
                                <a href="{{ route('orders.receipt', $order->id) }}" class="btn btn-sm btn-outline-info me-1" target="_blank">
                                    <i class="bi bi-receipt"></i> Receipt
                                </a>

                                {{-- VOID LOGIC --}}
                                @if($order->status == 'completed')
                                    @if(auth()->user()->role === 'admin')
                                        {{-- ADMIN: Direct Void --}}
                                        <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to VOID Order #{{ $order->id }}?')">
                                                Void
                                            </button>
                                        </form>
                                    @else
                                        {{-- EMPLOYEE: Request Void --}}
                                        <form action="{{ route('orders.requestVoid', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Request a void for Order #{{ $order->id }}?')">
                                                Request Void
                                            </button>
                                        </form>
                                    @endif

                                @elseif($order->status == 'void_pending')
                                    @if(auth()->user()->role === 'admin')
                                        {{-- ADMIN: Approve Void --}}
                                        <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Approve void request for Order #{{ $order->id }}?')">
                                                Approve Void
                                            </button>
                                        </form>
                                    @else
                                        {{-- EMPLOYEE: Pending Status --}}
                                        <button class="btn btn-sm btn-secondary" disabled>Pending Approval</button>
                                    @endif
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled>Voided</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection