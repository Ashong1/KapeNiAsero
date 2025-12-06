@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="col-md-5 col-lg-4">
        
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm" style="width: 80px; height: 80px;">
                <i class="fas fa-store text-primary-coffee fa-2x"></i>
            </div>
            <h4 class="fw-bold mt-3 text-dark">Start Your Shift</h4>
            <p class="text-secondary small">Good {{ now()->format('H') < 12 ? 'morning' : 'afternoon' }}, {{ Auth::user()->name }}!</p>
        </div>

        <div class="card card-custom border-0 shadow-lg" style="border-radius: 20px;">
            <div class="card-body p-4">
                
                {{-- 1. SHOW MIDDLEWARE ERROR (If they tried to force access another URL) --}}
                @if (session('error'))
                    <div class="alert alert-danger small mb-4 rounded-3 border-0 shadow-sm">
                        <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('shifts.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="start_cash" class="form-label small fw-bold text-secondary text-uppercase tracking-wide">
                            <i class="fas fa-coins me-1 text-primary-coffee"></i> Starting Cash
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0 text-secondary fw-bold" style="border-top-left-radius: 12px; border-bottom-left-radius: 12px;">₱</span>
                            
                            {{-- Add is-invalid class if validation fails --}}
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control bg-light border-start-0 fw-bold text-dark @error('start_cash') is-invalid @enderror" 
                                   name="start_cash" 
                                   placeholder="0.00" 
                                   autofocus
                                   style="border-top-right-radius: 12px; border-bottom-right-radius: 12px; font-size: 1.5rem;">
                                   
                            {{-- 2. SHOW VALIDATION ERROR (If input is empty) --}}
                            @error('start_cash')
                                <span class="invalid-feedback text-start small fw-bold mt-2" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-text text-muted small mt-2">
                            <i class="fas fa-info-circle me-1"></i> Count the physical cash in the drawer before starting.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-coffee w-100 py-3 rounded-4 fw-bold shadow-sm transition-all">
                        <i class="fas fa-check-circle me-2"></i> Open Register
                    </button>
                </form>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-secondary text-decoration-none small fw-bold">
                    Logout
                </button>
            </form>
        </div>

    </div>
</div>

<style>
    .transition-all { transition: all 0.2s ease; }
    .transition-all:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(111, 78, 55, 0.2) !important; }
    .text-primary-coffee { color: #6F4E37 !important; }
    .btn-primary-coffee { background-color: #6F4E37; border-color: #6F4E37; color: white; }
    .btn-primary-coffee:hover { background-color: #5A3D2B; border-color: #5A3D2B; }
    .input-group-text { border-color: #eee; }
    .form-control:focus { box-shadow: none; border-color: #6F4E37; }
</style>
@endsection