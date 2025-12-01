@extends('layouts.app')

@section('styles')
<style>
    /* Status Badges */
    .status-badge { font-size: 0.7rem; font-weight: 700; padding: 0.35em 0.8em; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; }
    .status-completed { background-color: #E8F5E9; color: #2E7D32; border: 1px solid #C8E6C9; }
    .status-void-pending { background-color: #FFF8E1; color: #F57F17; border: 1px solid #FFE082; }
    .status-voided { background-color: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }
    
    /* Action Buttons */
    .btn-icon { 
        width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; 
        border-radius: 10px; transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1); border: none; 
        font-size: 0.9rem; margin-left: 4px; text-decoration: none; 
        cursor: pointer; /* [ADDED] Makes it look clickable */
    }
    .btn-icon-print { background-color: #F3F4F6; color: #4B5563; }
    .btn-icon-print:hover { background-color: var(--primary-coffee); color: white; transform: translateY(-2px); }
    
    .btn-icon-void { background-color: #FEF2F2; color: #DC2626; }
    .btn-icon-void:hover { background-color: #DC2626; color: white; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2); }
    
    .btn-icon-request { background-color: #FFFBEB; color: #D97706; }
    .btn-icon-request:hover { background-color: #D97706; color: white; transform: translateY(-2px); }
    
    .btn-icon-approve { background-color: #ECFDF5; color: #059669; }
    .btn-icon-approve:hover { background-color: #059669; color: white; transform: translateY(-2px); }
    
    .btn-icon-disabled { background-color: #F3F4F6; color: #9CA3AF; cursor: not-allowed; }

    /* Header Styles */
    .table-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background-color: #fff;
        border-bottom: 1px solid #f0f0f0;
        border-radius: 15px 15px 0 0;
    }

    /* Footer Styles */
    .table-card-footer {
        padding: 1rem 1.5rem;
        background-color: #fff;
        border-top: 1px solid #f0f0f0;
        border-radius: 0 0 15px 15px;
    }

    /* --- PAGINATION STYLING (BRAND SYNC) --- */
    .pagination {
        margin-bottom: 0;
        gap: 5px; 
    }
    
    .page-link {
        color: var(--primary-coffee);
        border: 1px solid transparent;
        border-radius: 8px; 
        font-weight: 600;
        padding: 0.5rem 0.85rem;
        transition: all 0.2s ease;
    }

    .page-link:hover {
        background-color: var(--surface-cream);
        color: var(--primary-coffee-hover);
        border-color: rgba(111, 78, 55, 0.1);
        transform: translateY(-2px);
    }

    .page-link:focus {
        box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.15);
    }

    .page-item.active .page-link {
        background-color: var(--primary-coffee);
        border-color: var(--primary-coffee);
        color: white;
        box-shadow: 0 4px 10px rgba(111, 78, 55, 0.3);
    }

    .page-item.disabled .page-link {
        color: var(--text-secondary);
        background-color: transparent;
        opacity: 0.6;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        {{-- HEADER SECTION --}}
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
                            <td class="ps-4"><span class="fw-bold font-monospace text-primary-coffee">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="small text-secondary" style="font-size: 0.75rem;">{{ $order->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary" style="width:24px;height:24px;font-size:0.7rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span class="small fw-bold text-secondary">{{ $order->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td><span class="fw-bold text-dark fs-6">₱{{ number_format($order->total_price, 2) }}</span></td>
                            <td>
                                @if($order->status == 'completed') 
                                    <span class="status-badge status-completed"><i class="fas fa-check-circle me-1"></i> Paid</span>
                                @elseif($order->status == 'void_pending') 
                                    <span class="status-badge status-void-pending"><i class="fas fa-clock me-1"></i> Void Req.</span>
                                @elseif($order->status == 'voided') 
                                    <span class="status-badge status-voided"><i class="fas fa-ban me-1"></i> Voided</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center">
                                    {{-- Print Button --}}
                                    <a href="{{ route('orders.receipt', $order->id) }}" class="btn-icon btn-icon-print" target="_blank" title="Print Receipt">
                                        <i class="fas fa-print"></i>
                                    </a>

                                    @if($order->status == 'completed')
                                        @if(Auth::user()->role === 'admin')
                                            {{-- Admin: Void Button --}}
                                            <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline confirm-void-form" data-id="{{ $order->id }}">
                                                @csrf
                                                <button type="submit" class="btn-icon btn-icon-void" title="Void Order"><i class="fas fa-ban"></i></button>
                                            </form>
                                        @else
                                            {{-- Employee: Request Void Button --}}
                                            <form action="{{ route('orders.requestVoid', $order->id) }}" method="POST" class="d-inline confirm-request-form" data-id="{{ $order->id }}">
                                                @csrf
                                                <button type="submit" class="btn-icon btn-icon-request" title="Request Void"><i class="fas fa-flag"></i></button>
                                            </form>
                                        @endif
                                    @elseif($order->status == 'void_pending')
                                        @if(Auth::user()->role === 'admin')
                                            {{-- Admin: Approve Void --}}
                                            <form action="{{ route('orders.void', $order->id) }}" method="POST" class="d-inline confirm-approve-form" data-id="{{ $order->id }}">
                                                @csrf
                                                <button type="submit" class="btn-icon btn-icon-approve" title="Approve Void"><i class="fas fa-check"></i></button>
                                            </form>
                                        @else
                                            {{-- Employee: Disabled --}}
                                            <button class="btn-icon btn-icon-disabled" disabled title="Waiting for Approval"><i class="fas fa-hourglass-half"></i></button>
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
        </div>

        {{-- PAGINATION IN FOOTER --}}
        <div class="table-card-footer">
            {{ $orders->links() }}
        </div>
    </div>
</div>

@section('scripts')
{{-- [ADDED] SweetAlert2 Library --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Universal function to handle confirmation popups
        function setupAlert(selector, title, text, btnText, btnColor) {
            document.querySelectorAll(selector).forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Stop default form submit
                    
                    // Check if Swal is loaded properly
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: title,
                            text: text.replace(':id', this.getAttribute('data-id')),
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: btnColor,
                            cancelButtonText: 'Cancel',
                            confirmButtonText: btnText
                        }).then((r) => { 
                            if(r.isConfirmed) form.submit(); // Submit only if confirmed
                        });
                    } else {
                        // Browser default fallback
                        if(confirm(title + "\n" + text.replace(':id', this.getAttribute('data-id')))) {
                            form.submit();
                        }
                    }
                });
            });
        }

        // Attach alerts to specific forms
        setupAlert('.confirm-void-form', 'Void Order?', 'This will void Order #:id and return items to stock.', 'Yes, Void it', '#DC2626');
        setupAlert('.confirm-request-form', 'Request Void?', 'Submit a void request for Order #:id?', 'Submit Request', '#D97706');
        setupAlert('.confirm-approve-form', 'Approve Void?', 'Approve pending void for Order #:id?', 'Approve', '#059669');
    });
</script>
@endsection
@endsection