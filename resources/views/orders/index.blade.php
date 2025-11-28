@extends('layouts.app')

@section('content')
    <div class="card card-custom">
        <div class="card-header bg-white border-bottom p-4">
            <h5 class="fw-bold m-0"><i class="fas fa-history me-2 text-primary-coffee"></i> Order History</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Order ID</th>
                            <th>Date & Time</th> <th>Total</th>
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
                                {{-- View Receipt Button --}}
                                <a href="{{ route('orders.receipt', $order->id) }}" class="btn btn-sm btn-light text-secondary me-1 border" target="_blank" title="Receipt">
                                    <i class="fas fa-print"></i>
                                </a>

                                {{-- VOID LOGIC START --}}
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
                                                <i class="fas fa-flag me-1"></i> Request
                                            </button>
                                        </form>
                                    @endif

                                @elseif($order->status == 'void_pending')
                                    @if(auth()->user()->role === 'admin')
                                        {{-- ADMIN: Approve Void Request --}}
                                        <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Approve void request for Order #{{ $order->id }}?')">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                        </form>
                                    @else
                                        {{-- EMPLOYEE: Waiting Status --}}
                                        <button class="btn btn-sm btn-light text-muted border" disabled>
                                            <i class="fas fa-clock me-1"></i> Pending
                                        </button>
                                    @endif
                                @else
                                    {{-- Order is already voided --}}
                                    <button class="btn btn-sm btn-light border-0" disabled>
                                        <i class="fas fa-times-circle text-muted"></i>
                                    </button>
                                @endif
                                {{-- VOID LOGIC END --}}
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
            <div class="p-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection