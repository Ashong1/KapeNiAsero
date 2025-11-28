@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card card-custom p-4">
            
            <div class="text-center mb-4">
                <div class="bg-warning-subtle rounded-circle d-inline-flex p-3 mb-3 shadow-sm">
                    <i class="fas fa-edit fa-2x text-warning-emphasis"></i>
                </div>
                <h4 class="fw-bold text-dark">Edit Supplier</h4>
                <p class="text-secondary small">Update supplier information</p>
            </div>

            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-uppercase small fw-bold text-secondary">Company Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;">
                                <i class="fas fa-building text-muted"></i>
                            </span>
                            <input type="text" name="name" 
                                   class="form-control form-control-lg fs-6 border-start-0 ps-0" 
                                   value="{{ $supplier->name }}" 
                                   required 
                                   style="border-radius: 0 12px 12px 0; padding: 0.8rem;">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-uppercase small fw-bold text-secondary">Contact Person</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;">
                                <i class="fas fa-user text-muted"></i>
                            </span>
                            <input type="text" name="contact_person" 
                                   class="form-control form-control-lg fs-6 border-start-0 ps-0" 
                                   value="{{ $supplier->contact_person }}" 
                                   style="border-radius: 0 12px 12px 0; padding: 0.8rem;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-uppercase small fw-bold text-secondary">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;">
                                <i class="fas fa-envelope text-muted"></i>
                            </span>
                            <input type="email" name="email" 
                                   class="form-control form-control-lg fs-6 border-start-0 ps-0" 
                                   value="{{ $supplier->email }}" 
                                   style="border-radius: 0 12px 12px 0; padding: 0.8rem;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-uppercase small fw-bold text-secondary">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px;">
                                <i class="fas fa-phone text-muted"></i>
                            </span>
                            <input type="text" name="phone" 
                                   class="form-control form-control-lg fs-6 border-start-0 ps-0" 
                                   value="{{ $supplier->phone }}" 
                                   style="border-radius: 0 12px 12px 0; padding: 0.8rem;">
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('suppliers.index') }}" 
                       class="btn btn-light w-50 text-secondary fw-bold d-flex align-items-center justify-content-center" 
                       style="border-radius: 12px; padding: 0.8rem;">
                       Cancel
                    </a>
                    
                    <button type="submit" 
                            class="btn btn-warning w-50 fw-bold d-flex align-items-center justify-content-center text-dark" 
                            style="border-radius: 12px; padding: 0.8rem; background-color: #ffc107; border: none;">
                        <i class="fas fa-save me-2"></i> Update Supplier
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection