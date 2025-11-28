@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Supplier</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Company Name</label>
                            <input type="text" name="name" value="{{ $supplier->name }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Contact Person</label>
                            <input type="text" name="contact_person" value="{{ $supplier->contact_person }}" class="form-control">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Email Address</label>
                                <input type="email" name="email" value="{{ $supplier->email }}" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Phone Number</label>
                                <input type="text" name="phone" value="{{ $supplier->phone }}" class="form-control">
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-warning btn-lg shadow-sm">
                                <i class="fas fa-save me-2"></i> Update Supplier
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