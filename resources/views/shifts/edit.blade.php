@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="col-md-5 col-lg-4">

        <div class="card card-custom border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
            
            {{-- Header --}}
            <div class="card-header bg-white border-bottom p-4 text-center pb-3">
                <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-door-closed fa-xl"></i>
                </div>
                <h5 class="fw-bold m-0 text-dark">End Shift & Close</h5>
                <p class="text-muted small m-0">{{ now()->format('F d, Y • h:i A') }}</p>
            </div>

            <div class="card-body p-4">
                
                {{-- Receipt Style Summary --}}
                <div class="bg-light p-3 rounded-3 mb-4 border border-dashed border-secondary border-opacity-25">
                    <h6 class="fw-bold text-uppercase small text-secondary mb-3">System Expected</h6>
                    
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Starting Cash</span>
                        <span class="fw-bold text-dark">₱{{ number_format($shift->start_cash, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Total Cash Sales</span>
                        <span class="fw-bold text-success">+ ₱{{ number_format($cashSales, 2) }}</span>
                    </div>
                    <hr class="my-2 text-secondary opacity-25">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark small">Expected in Drawer</span>
                        <span class="fw-bold fs-5 text-dark">₱{{ number_format($expectedCash, 2) }}</span>
                    </div>
                </div>

                {{-- Input Form --}}
                <form method="POST" action="{{ route('shifts.update', $shift->id) }}" id="closeShiftForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="end_cash" class="form-label small fw-bold text-danger text-uppercase tracking-wide">
                            <i class="fas fa-hand-holding-dollar me-1"></i> Actual Count
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-danger text-danger fw-bold" style="border-top-left-radius: 12px; border-bottom-left-radius: 12px;">₱</span>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control border-danger text-danger fw-bold" 
                                   name="end_cash" 
                                   required 
                                   placeholder="0.00" 
                                   autofocus
                                   style="border-top-right-radius: 12px; border-bottom-right-radius: 12px; font-size: 1.5rem; background: #fff5f5;">
                        </div>
                        <div class="form-text text-muted small mt-2">
                            Enter the total cash amount currently in the drawer.
                        </div>
                    </div>

                    <button type="button" class="btn btn-danger w-100 py-3 rounded-4 fw-bold shadow-sm transition-hover" onclick="confirmClose()">
                        Close Register & Logout <i class="fas fa-sign-out-alt ms-2"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="text-secondary text-decoration-none small fw-bold">
                Cancel
            </a>
        </div>

    </div>
</div>

{{-- SweetAlert for Confirmation --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmClose() {
        Swal.fire({
            title: 'Close Register?',
            text: "This will end your shift and log you out.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            confirmButtonText: 'Yes, Close It',
            cancelButtonText: 'Wait, go back'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('closeShiftForm').submit();
            }
        });
    }
</script>

<style>
    .border-dashed { border-style: dashed !important; }
    .transition-hover { transition: all 0.2s; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(220, 38, 38, 0.2); }
</style>
@endsection