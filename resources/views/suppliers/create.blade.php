@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-10">
            
            <!-- Main Card -->
            <div class="card shadow-lg">
                
                <!-- Card Header -->
                <div class="card-header text-center pt-4 pb-3">
                    <h3 class="fw-bold" style="color: var(--color-sienna);">
                        <i class="fas fa-truck me-2"></i>Register New Supplier
                    </h3>
                    <p class="mb-0 text-muted small text-light opacity-75">Enter the details of your new partner</p>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4">
                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf
                        
                        <!-- Section: Company Info -->
                        <h6 class="text-uppercase fw-bold mb-3 opacity-50" style="color: var(--color-crema); font-size: 0.8rem; letter-spacing: 1px;">
                            Company Details
                        </h6>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Company Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-light"><i class="fas fa-building"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 ps-0" placeholder="e.g. Benguet Coffee Farmers Inc." required>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Contact Info -->
                        <h6 class="text-uppercase fw-bold mb-3 mt-4 opacity-50" style="color: var(--color-crema); font-size: 0.8rem; letter-spacing: 1px;">
                            Contact Information
                        </h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Contact Person</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-light"><i class="fas fa-user"></i></span>
                                    <input type="text" name="contact_person" class="form-control border-start-0 ps-0" placeholder="e.g. Mr. Juan Dela Cruz">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-light"><i class="fas fa-phone"></i></span>
                                    <input type="text" name="phone" class="form-control border-start-0 ps-0" placeholder="e.g. 0917-123-4567">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-light"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="orders@company.com" required>
                                </div>
                            </div>
                        </div>

                        <hr style="border-color: var(--color-sienna); opacity: 0.3;">

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('suppliers.index') }}" class="text-decoration-none text-light opacity-75 hover-opacity-100">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm" style="background-color: var(--color-sienna); border: none; color: var(--color-mocha);">
                                <i class="fas fa-check-circle me-2"></i> Save Supplier
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Small inline style tweak for the input group icons to match the form theme --}}
<style>
    .input-group-text {
        border-color: var(--color-cream) !important;
    }
    .form-control {
        border-left: none !important;
    }
    .form-control:focus {
        box-shadow: none !important; 
        border-color: var(--color-sienna) !important;
    }
    .input-group:focus-within .input-group-text {
        border-color: var(--color-sienna) !important;
        color: var(--color-sienna) !important;
    }
</style>
@endsection