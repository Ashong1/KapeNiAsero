@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Register New Supplier</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Company Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Benguet Beans Co." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" placeholder="e.g. Mr. Juan Dela Cruz">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="orders@company.com" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Phone Number</label>
                                <input type="text" name="phone" class="form-control" placeholder="0917-xxx-xxxx">
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="fas fa-save me-2"></i> Save Supplier
                            </button>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-link text-muted text-decoration-none">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection