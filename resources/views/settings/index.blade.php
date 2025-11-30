@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark m-0"><i class="fas fa-cogs me-2 text-primary-coffee"></i>System Settings</h4>
            <p class="text-secondary small m-0">Manage store details and receipt configuration.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom p-4">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="fw-bold text-secondary text-uppercase mb-3 small border-bottom pb-2">Store Information</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Store Name</label>
                        <input type="text" name="store_name" class="form-control" value="{{ $settings['store_name'] ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Address</label>
                        <input type="text" name="store_address" class="form-control" value="{{ $settings['store_address'] ?? '' }}" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Contact Number</label>
                            <input type="text" name="store_phone" class="form-control" value="{{ $settings['store_phone'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">TIN</label>
                            <input type="text" name="store_tin" class="form-control" value="{{ $settings['store_tin'] ?? '' }}">
                        </div>
                    </div>

                    <h6 class="fw-bold text-secondary text-uppercase mb-3 small border-bottom pb-2 mt-4">Financial & Receipt</h6>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">VAT Rate (%)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="tax_rate" class="form-control" value="{{ $settings['tax_rate'] ?? '12' }}" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <div class="form-text small">Used to calculate Vatable Sales on receipts.</div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Accreditation No.</label>
                            <input type="text" name="accreditation_no" class="form-control" value="{{ $settings['accreditation_no'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">PTU Number</label>
                            <input type="text" name="ptu_number" class="form-control" value="{{ $settings['ptu_number'] ?? '' }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary-coffee px-4">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection