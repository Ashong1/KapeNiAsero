@extends('layouts.app')

@section('styles')
<style>
    /* --- STATUS BADGES --- */
    .status-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.35em 0.8em;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-block;
    }
    .status-completed { background-color: #E8F5E9; color: #2E7D32; border: 1px solid #C8E6C9; }
    .status-void-pending { background-color: #FFF8E1; color: #F57F17; border: 1px solid #FFE082; }
    .status-voided { background-color: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }

    /* --- ACTION BUTTONS --- */
    .btn-icon {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: none;
        font-size: 0.9rem;
        margin-left: 4px;
        text-decoration: none;
    }
    
    /* Print Button (Gray) */
    .btn-icon-print { background-color: #F3F4F6; color: #4B5563; }
    .btn-icon-print:hover { background-color: var(--primary-coffee); color: white; transform: translateY(-2px); }
    
    /* Void Button (Red) */
    .btn-icon-void { background-color: #FEF2F2; color: #DC2626; }
    .btn-icon-void:hover { background-color: #DC2626; color: white; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2); }

    /* Request Button (Orange) */
    .btn-icon-request { background-color: #FFFBEB; color: #D97706; }
    .btn-icon-request:hover { background-color: #D97706; color: white; transform: translateY(-2px); }

    /* Approve Button (Green) */
    .btn-icon-approve { background-color: #ECFDF5; color: #059669; }
    .btn-icon-approve:hover { background-color: #059669; color: white; transform: translateY(-2px); }

    /* Disabled/Pending */
    .btn-icon-disabled { background-color: #F3F4F6; color: #9CA3AF; cursor: not-allowed; }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="table-card-header">
            <h5 class="fw-bold m-0 text-dark">
                <i class="fas fa-history me-2 text-primary-coffee"></i>Order History
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3">Order ID</th>
                            <th>Date & Time</th>
                            <th>Cashier</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="{{ $order->status === 'voided' ? 'opacity-50' : '' }}">
                            {{-- Order ID --}}
                            <td class="ps-4">
                                <span class="fw-bold font-monospace text-primary-coffee">
                                    #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            
                            {{-- Date --}}
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="small text-secondary" style="font-size: 0.75rem;">{{ $order->created_at->format('h:i A') }}</div>
                            </td>

                            {{-- Cashier --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary" style="width:24px;height:24px;font-size:0.7rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span class="small fw-bold text-secondary">{{ $order->user->name ?? 'System' }}</span>
                                </div>
                            </td>

                            {{-- Total --}}
                            <td>
                                <span class="fw-bold text-dark fs-6">₱{{ number_format($order->total_price, 2) }}</span>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($order->status == 'completed')
                                    <span class="status-badge status-completed"><i class="fas fa-check-circle me-1"></i> Paid</span>
                                @elseif($order->status == 'void_pending')
                                    <span class="status-badge status-void-pending"><i class="fas fa-clock me-1"></i> Void Req.</span>
                                @elseif($order->status == 'voided')
                                    <span class="status-badge status-voided"><i class="fas fa-ban me-1"></i> Voided</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center">
                                    
                                    {{-- 1. VIEW RECEIPT (Always visible unless voided, technically you can still view voided receipts but let's keep it clean) --}}
                                    <a href="{{ route('orders.receipt', $order->id) }}" class="btn-icon btn-icon-print" target="_blank" title="Print Receipt">
                                        <i class="fas fa-print"></i>
                                    </a>

                                    {{-- 2. VOID LOGIC --}}
                                    @if($order->status == 'completed')
                                        @if(Auth::user()->role === 'admin')
                                            {{-- ADMIN: Direct Void --}}
                                            <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-icon btn-icon-void" onclick="return confirm('Are you sure you want to VOID Order #{{ $order->id }}? This returns items to stock.')" title="Void Order">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @else
                                            {{-- STAFF: Request Void --}}
                                            <form action="{{ route('orders.requestVoid', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-icon btn-icon-request" onclick="return confirm('Request a void for Order #{{ $order->id }}?')" title="Request Void">
                                                    <i class="fas fa-flag"></i>
                                                </button>
                                            </form>
                                        @endif

                                    @elseif($order->status == 'void_pending')
                                        @if(Auth::user()->role === 'admin')
                                            {{-- ADMIN: Approve Void --}}
                                            <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-icon btn-icon-approve" onclick="return confirm('Approve void request for Order #{{ $order->id }}?')" title="Approve Void">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @else
                                            {{-- STAFF: Pending Indicator --}}
                                            <button class="btn-icon btn-icon-disabled" disabled title="Waiting for Admin Approval">
                                                <i class="fas fa-hourglass-half"></i>
                                            </button>
                                        @endif
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted opacity-50">
                                    <i class="fas fa-receipt fa-3x mb-3"></i>
                                    <p class="fw-medium mb-0">No order history found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="p-4 border-top">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection